<?php

namespace app\modules\item\controllers;

use app\controllers\Controller;
use app\modules\booking\models\Booking;
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
        if (isset($_POST['button'])) {
            $button = $_POST['button'];
        }
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
                    return $this->redirect(['/item/' . $model->item->id . '/edit#publishing']);
                }
                if ($button == "submit-preview") {
                    return $this->redirect(['/item/' . $model->item->id]);
                }
                if ($button == "submit-publish") {
                    if ($model->isPublishable() && $model->is_available === 0) {
                        if (
                            !array_key_exists('edit-item', \Yii::$app->request->post()) ||
                            !array_key_exists('rules', \Yii::$app->request->post()['edit-item'])
                        ) {
                            \Yii::$app->session->addFlash('warning', \Yii::t('item',
                                'Something went wrong in the process, please try again.'));
                            return $this->redirect(['/item/' . $model->item->id . '/edit']);
                        }
                        if (\Yii::$app->request->post()['edit-item']['rules'] != 1) {
                            \Yii::$app->session->addFlash('warning', \Yii::t('item',
                                'The terms and conditions have to be accepted before an item can be published'));
                            return $this->redirect(['/item/' . $model->item->id . '/edit']);
                        }
                        $item->is_available = 1;
                        $item->save();
                        return $this->redirect(['/item/' . $model->item->id, 'new_publish' => true]);
                    }
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
            // try to find order
            $orderIhm = ItemHasMedia::find()->where(['item_id' => $item->id, 'media_id' => $i->id,])->orderBy('order DESC')->one();
            if ($orderIhm == null) {
                $order = 1;
            } else {
                if ($orderIhm->order == null) {
                    $order = 1;
                } else {
                    $order = $orderIhm->order + 1;
                }
            }
            $ihm = new ItemHasMedia();
            $ihm->setAttributes([
                'item_id' => $item->id,
                'media_id' => $i->id,
                'order' => $order
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

    public function actionUnpublish($id)
    {
        $item = Item::find()->where(['id' => $id])->one();
        if ($item == null) {
            throw new NotFoundHttpException('Item does not exist');
        }
        if (!$item->isOwner()) {
            throw new ForbiddenHttpException();
        }

        // check bookings
        $bookingInFuture = false;
        $bookings = Booking::find()->where(['item_id' => $id])->all();
        if (count($bookings) !== 0) {
            // there exists at least one booking
            foreach ($bookings as $booking) {
                if ($booking->status !== Booking::AWAITING_PAYMENT) {
                    if ($booking->time_to > time()) {
                        // there exists an item which has not the status awaiting payment
                        // and is reserved in the future, so the item can not be removed
                        $bookingInFuture = true;
                    }
                }
            }
        }

        if ($bookingInFuture) {
            \Yii::$app->session->addFlash('warning', \Yii::t('item',
                'A booking was made for this product. The product could not be unpublished.'));
            return $this->redirect(['/item/list']);
        } else {
            $item->is_available = 0;
            $item->save();
            Yii::$app->session->addFlash('info', \Yii::t('item', 'The product has been made unavailable'));
            return $this->redirect('@web/item/' . $id . '/edit#publishing');
        }
    }
}

