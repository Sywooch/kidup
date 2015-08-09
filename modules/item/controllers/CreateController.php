<?php

namespace app\modules\item\controllers;

use app\controllers\Controller;
use app\modules\images\components\ImageHelper;
use app\modules\images\components\ImageManager;
use app\modules\item\forms\Create;
use app\modules\item\forms\Edit;
use app\modules\item\models\Category;
use app\modules\item\models\Item;
use app\modules\item\models\ItemHasMedia;
use app\modules\item\models\Media;
use Carbon\Carbon;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class CreateController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'upload', 'edit', 'delete-upload'],
                'rules' => [
                    [
                        // only authenticated
                        'allow' => true,
                        'actions' => ['index', 'upload', 'edit', 'delete-upload'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new Create();
        $categories = Category::find()->where(['type' => Category::TYPE_MAIN])->all();

        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());
            $model->categories = \Yii::$app->request->post($model->formName())['categories'];
            if ($model->save()) {
                $this->redirect(['/item/' . $model->item->id . '/edit']);
            }
        }

        return $this->render('index', [
            'model' => $model,
            'categories' => $categories
        ]);
    }

    public function actionEdit($id, $button = null)
    {
        $item = Item::find()->where(['id' => $id])->one();
        if ($item == null) {
            throw new NotFoundHttpException('Item does not exist');
        }
        if (!$item->isOwner()) {
            throw new ForbiddenHttpException();
        }

        $model = new Edit($item);

        if (\Yii::$app->request->isPost) {
            if ($button == 'submit-save') {
                $model->scenario = 'save';
            }
            if ($button == 'submit-preview') {
                $model->setScenario('save');
            }
            if ($button == 'submit-publish') {
                $model->setScenario('default');
            }
            $model->load(\Yii::$app->request->post());
            $model->categories = \Yii::$app->request->post($model->formName())['categories'];
            if ($model->save()) {
                if ($button == "submit-save") {
                    $this->redirect(['/item/' . $model->item->id . '/edit']);
                }
                if ($button == "submit-preview") {
                    $this->redirect(['/item/' . $model->item->id]);
                }
                if ($button == "submit-publish") {
                    // todo check stuff here
                    if (\Yii::$app->request->post()['edit-item']['rules'] != 1) {
                        \Yii::$app->session->addFlash('warning', \Yii::t('item',
                            'The terms and conditions have to be accepted before an item can be published'));
                        return $this->redirect(['/item/' . $model->item->id . '/edit']);
                    }
                    $item->is_available = 1;
                    $item->save();
                    $this->redirect(['/item/' . $model->item->id]);
                }
            }
        }

        $categories = [
            Category::TYPE_MAIN => Category::findAll(['type' => Category::TYPE_MAIN]),
            Category::TYPE_SPECIAL => Category::findAll(['type' => Category::TYPE_SPECIAL]),
            Category::TYPE_AGE => Category::findAll(['type' => Category::TYPE_AGE])
        ];

        // preload images to load in dropzone
        $preload = [];
        $allMedia = Media::find()->where(['item_has_media.item_id' => $id])
            ->innerJoinWith('itemHasMedia')
            ->orderBy('item_has_media.order')
            ->all();
        foreach ($allMedia as $media) {
            $preload[] = [
                'name' => ImageHelper::url($media->file_name, ['q' => 90, 'w' => 120, 'h' => 120, 'fit' => 'crop']),
                'size' => 10,
            ];
        }

        // price suggestions
        $suggestions = false;
        if ($model->newPrice) {
            $suggestions = [10, 20, 30];
        }

        return $this->render('edit', [
            'model' => $model,
            'preload' => Json::encode($preload),
            'fileUrl' => Url::to(['/file/'], true),
            'categories' => $categories,
            'suggestions' => $suggestions
        ]);
    }

    public function actionUpload($item_id)
    {
        if (!\Yii::$app->request->isPost) {
            throw new ForbiddenHttpException();
        }
        $item = Item::findOne($item_id);
        if ($item == null) {
            throw new NotFoundHttpException();
        }
        if (!$item->isOwner()) {
            throw new ForbiddenHttpException();
        }

        $image = UploadedFile::getInstanceByName('file');
        if (!in_array($image->extension, ['png', 'jpg'])) {
            Yii::$app->session->addFlash('warning', \Yii::t('item', "File format not allowed"));
            return false;
        }
        $i = new Media();
        $i->setAttributes([
            'user_id' => \Yii::$app->user->id,
            'file_name' => (new ImageManager())->upload($image),
            'type' => Media::TYPE_IMG,
            'storage' => Media::LOC_LOCAL,
            'created_at' => Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp,
            'updated_at' => Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp,
        ]);
        $i->save();
        if ($i->save()) {
            $ihm = new ItemHasMedia();
            $ihm->setAttributes([
                'item_id' => $item->id,
                'media_id' => $i->id,

            ]);
            $ihm->save();
        }

        return $i->save();
    }

    public function actionDeleteUpload($item_id)
    {
        if (!\Yii::$app->request->isPost) {
            throw new ForbiddenHttpException('Only POST accepted');
        }
        $item = Item::findOne($item_id);
        if ($item == null) {
            throw new NotFoundHttpException('Item not found');
        }
        if (!$item->isOwner()) {
            throw new ForbiddenHttpException("Not the owner of this item");
        }
        $media = Media::find()->where([
            'file_name' => ImageHelper::urlToFilename(\Yii::$app->request->post('imageId'))
        ])->one();

        if ($media == null) {
            throw new NotFoundHttpException();
        }

        (new ImageManager)->delete($media->file_name);

        foreach ($media->itemHasMedia as $m) {
            $m->delete();
        }

        $media->delete();

        return true;
    }

    public function actionImageSort($item_id)
    {
        if (!\Yii::$app->request->isPost) {
            throw new ForbiddenHttpException();
        }
        $item = Item::findOne($item_id);
        if ($item == null) {
            throw new NotFoundHttpException();
        }
        if (!$item->isOwner()) {
            throw new ForbiddenHttpException();
        }
        $input = \Yii::$app->request->post('order');
        if (is_array($input)) {
            foreach ($input as $order => &$image) {
                $imageName = explode("/", $image);
                foreach ($imageName as $i) {
                    if (empty($i)) {
                        continue;
                    }
                    if (strpos($i, '.png') !== false || strpos($i, '.jpg') !== false) {
                        $image = explode("?", $i)[0];
                    }
                }
                $media = Media::find()->where(['file_name' => $image])->one();
                if ($media->user_id !== \Yii::$app->user->id) {
                    throw new ForbiddenHttpException();
                }
                $connection = ItemHasMedia::find()->where([
                    'item_id' => $item_id,
                    'media_id' => $media->id
                ])->one();
                if ($connection !== null) {
                    $connection->order = $order + 1;
                    $connection->save();
                }
            }
        }
    }
}

