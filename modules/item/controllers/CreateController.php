<?php

namespace app\modules\item\controllers;

use app\controllers\Controller;
use app\modules\item\components\MediaManager;
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
        foreach ($item->media as $media) {
            $preload[] = [
                'name' => MediaManager::fileversion($media->file_name, MediaManager::THUMB),
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
        if(!in_array($image->extension, ['png', 'jpg'])){
            throw new NotAcceptableHttpException("File format not allowed");
        }
        $i = new Media();
        $i->setAttributes([
            'user_id' => \Yii::$app->user->id,
            'file_name' => MediaManager::set($image, \Yii::$app->user->id),
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
            throw new ForbiddenHttpException();
        }
        $item = Item::findOne($item_id);
        if ($item == null) {
            throw new NotFoundHttpException();
        }
        if (!$item->isOwner()) {
            throw new ForbiddenHttpException();
        }
        $media = Media::find()->where([
            'file_name' => str_replace("_thumb", "", \Yii::$app->request->post('imageId'))
        ])->one();

        if ($media == null) {
            throw new NotFoundHttpException();
        }

        MediaManager::delete($media->file_name);

        foreach ($media->itemHasMedia as $m) {
            $m->delete();
        }

        $media->delete();

        return true;
    }

    public function actionImageSort($item_id){
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
        if(is_array($input)){
            foreach($input as $order => &$image){
                $media = Media::find()->where(['file_name' => str_replace('_thumb', '' , $image)])->one();
                if($media->user_id !==\Yii::$app->user->id){
                    throw new ForbiddenHttpException();
                }
                $connection = ItemHasMedia::find()->where([
                    'item_id' => $item_id,
                    'media_id' => $media->id
                ])->one();
                if($connection !== null){
                    $connection->order=$order+1;
                    $connection->save();
                }
            }
        }
    }
}

