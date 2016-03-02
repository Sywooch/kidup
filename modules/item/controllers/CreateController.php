<?php

namespace item\controllers;

use app\extended\web\Controller;
use booking\models\Booking;
use Carbon\Carbon;
use images\components\ImageHelper;
use images\components\ImageManager;
use item\forms\Create;
use item\forms\Edit;
use item\forms\LocationForm;
use item\models\Item;
use item\models\ItemHasMedia;
use item\models\Media;
use search\models\ItemSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

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

        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());
            if ($model->save()) {
                $this->redirect('@web/item/create/edit-basics?id=' . $model->item->id );
            }
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionAddLocation()
    {
        if(\Yii::$app->request->isAjax){
            $locationForm = new LocationForm();
            $locationForm->load(Yii::$app->request->post());
            $locationForm->loadItem();
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($locationForm);
        }
        if (\Yii::$app->request->isPost) {
            $locationForm = new LocationForm();
            $locationForm->load(Yii::$app->request->post());
            $locationForm->loadItem();
            if ($locationForm->save()) {
                return $this->redirect('@web/item/create/edit-photos?id='.$locationForm->item_id);
            }else{
                Yii::$app->session->addFlash('item', \Yii::t('item.create.flash_address_not_found',
                    "That address couldn't be found, is it valid?"));
                return $this->redirect('@web/item/create/edit-location?id='.$locationForm->item_id);
            }
        }
        return false;
    }

    public function actionEditBasics($id)
    {
        $i = $this->defaultPage($id, 'basics');
        if($i instanceof Response){
            return $i;
        }

        return $this->render('wrapper', [
            'item' => $i['item'],
            'model' => $i['model'],
            'page' => 'basics/basics',
            'pageParams' => [
            ]
        ]);
    }

    public function actionEditDescription($id)
    {
        $i = $this->defaultPage($id, 'description');
        if($i instanceof Response){
            return $i;
        }

        return $this->render('wrapper', [
            'item' => $i['item'],
            'model' => $i['model'],
            'page' => 'description/description',
            'pageParams' => [
            ]
        ]);
    }

    public function actionEditLocation($id)
    {
        $i = $this->defaultPage($id, 'location');
        if($i instanceof Response){
            return $i;
        }

        $locationForm = new LocationForm();

        return $this->render('wrapper', [
            'item' => $i['item'],
            'model' => $i['model'],
            'page' => 'location/location',
            'pageParams' => [
                'locationModel' => $locationForm
            ],
            'rightColumn' => 'location/location_modal',
            'rightColumnParams' => [
                'model' => $locationForm,
                'from' =>  $i['model'],
                'itemId' => $i['item']->id
            ]
        ]);
    }

    public function actionEditPhotos($id)
    {
        $i = $this->defaultPage($id, 'photos');
        if($i instanceof Response){
            return $i;
        }

        return $this->render('wrapper', [
            'item' => $i['item'],
            'model' => $i['model'],
            'page' => 'photos/photos',
            'pageParams' => [
                'preload' => $i['item']->preloadMedia(),
                'fileUrl' => Url::to(['/file/'], true),
            ]
        ]);
    }

    public function actionEditPricing($id)
    {
        $i = $this->defaultPage($id, 'pricing');
        if($i instanceof Response){
            return $i;
        }

        return $this->render('wrapper', [
            'item' => $i['item'],
            'model' => $i['model'],
            'page' => 'pricing/pricing',
            'pageParams' => [],
            'rightColumn' => 'pricing/suggestion',
            'rightColumnParams' => []
        ]);
    }

    public function actionEditPublish($id, $publish = false)
    {
        $item = $this->getItem($id);
        if($item->is_available == 1){
            return $this->redirect('@web/item/' . $id);
        }
        $i = $this->defaultPage($id, 'default');
        if($i instanceof Response){
            return $i;
        }

        $isValid = $i['model']->isScenarioValid('default');
        if (!$isValid) {
            Yii::$app->session->addFlash('info', \Yii::t('item.create.flash_please_finish_steps',
                'Please finish all required steps before publishing!'));
            return $this->redirect('@web/item/create/edit-basics?id=' . $id);
        }
        if($publish !== false){
            $item->is_available = 1;
            $item->save(false); // save, but don't check if valid, should be done by this point
            ItemSearch::updateSearch(); // enables the item to be found
            return $this->redirect('@web/item/'.$id.'?new_publish=true');
        }
        return $this->render('wrapper', [
            'item' => $i['item'],
            'model' => $i['model'],
            'page' => 'publish',
            'pageParams' => [],
            'rightColumnParams' => []
        ]);
    }

    private function defaultPage($id, $scenario)
    {
        $item = $this->getItem($id);

        $model = new Edit($item);
        $model->setScenario($scenario);
        if (\Yii::$app->request->isPost) {

            $model->load(\Yii::$app->request->post());
            $model->loadAndSaveFacets();
            $model->save(); // this does all the validation and redirecting

            $pageOrder = ['basics', 'description', 'location', 'photos', 'pricing', 'publish'];
            $nextPage = $pageOrder[min(array_search($scenario, $pageOrder) + 1, 6)];
            $prevPage = $pageOrder[max(0, array_search($scenario, $pageOrder) - 1)];
            if (isset(\Yii::$app->request->post()['btn-back'])) {
                return Yii::$app->response->redirect('@web/item/create/edit-' . $prevPage . '?id=' . $item->id);
            } else {
                $url = '@web/item/create/edit-' . $nextPage . '?id=' . $item->id;
                return Yii::$app->response->redirect($url);
            }
        }
        return ['item' => $item, 'model' => $model];
    }

    private function getItem($id)
    {
        /**
         * @var Item $item
         */
        $item = Item::find()->where(['id' => $id])->one();
        if ($item == null) {
            throw new NotFoundHttpException('Item does not exist');
        }
        if (!$item->isOwner()) {
            throw new ForbiddenHttpException('Item does not belong to you.');
        }

        return $item;
    }

    public function actionUpload($item_id)
    {
        if (!\Yii::$app->request->isPost) {
            throw new ForbiddenHttpException();
        }
        $item = $this->getItem($item_id);

        $image = UploadedFile::getInstanceByName('file');
        if($image == null){
            throw new HttpException("No file uploaded: uploaded file should have index 'file'");
        }
        if (!in_array($image->extension, ['png', 'jpg', 'pjpg'])) {
            throw new BadRequestHttpException("File format not allowed");
        }
        $i = new Media();
        $i->setAttributes([
            'user_id' => \Yii::$app->user->id,
            'file_name' => (new ImageManager())->upload($image),
            'created_at' => Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp,
            'updated_at' => Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp,
        ]);
        $i->save();
        if ($i->save()) {
            // try to find order
            $orderIhm = ItemHasMedia::find()->where([
                'item_id' => $item->id,
                'media_id' => $i->id,
            ])->orderBy('order DESC')->one();
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

        $item = $this->getItem($item_id);

        $media = Media::find()->where([
            'file_name' => ImageHelper::urlToFilename(\Yii::$app->request->post('imageId'))
        ])->one();

        if ($media == null) {
            throw new NotFoundHttpException("Media not found");
        }
        // dont remove the original, only the reference
        //        (new ImageManager)->delete($media->file_name);

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
        $item = $this->getItem($id);

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
            \Yii::$app->session->addFlash('warning', \Yii::t('item.create.flash_active_booking_cannot_unpublish',
                'A booking was made for this product. The product could not be unpublished.'));
            return $this->redirect(['/item/list']);
        } else {
            $item->is_available = 0;
            $item->save();
            Yii::$app->session->addFlash('info', \Yii::t('item.create.item_unavailable', 'The product has been made unavailable'));
            return $this->redirect('@web/item/create/edit-basics?id=' . $id);
        }
    }


}

