<?php
namespace api\v1\controllers;

use api\v1\models\Item;
use api\v1\models\Media;
use images\components\ImageManager;
use item\models\itemHasMedia\ItemHasMedia;
use search\components\ItemSearchDb;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class MediaController extends Controller
{

    public function init()
    {
        $this->modelClass = Media::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['view'],
            'user' => ['create', 'delete', 'index', 'image-sort']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['create']);
        return $actions;
    }

    // returns all the medias from a user
    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Media::find()->where(['user_id' => \Yii::$app->user->id])
        ]);
    }

    // returns all the medias from a user
    public function actionCreate($item_id = null, $profile_pic = false)
    {
        if ($item_id !== null) {
            $item = Item::find()->where(['id' => $item_id])->one();
            if ($item == null) {
                throw new NotFoundHttpException("Item not found");
            }
            /*
             * @var $item Item
             */
            if (!$item->hasModifyRights()) {
                throw new ForbiddenHttpException();
            }
            if (\Yii::$app->runAction('item/create/upload', ['item_id' => $item_id]) == true) {
                $media = Media::find()->where(['user_id' => \Yii::$app->user->id])->orderBy('created_at DESC')->one();
                return $media;
            }
            throw new BadRequestHttpException("Error occured during uploading the image.");
        } else {
            $img = UploadedFile::getInstanceByName('file');
            $p = \Yii::$app->user->identity->profile;
            $imgUrl =  (new ImageManager())->upload($img);
            if ($profile_pic !== false) {
                $p->setAttribute('img', $imgUrl);
                if ($p->save()) {
                    return true;
                } else {
                    return $p->getErrors();
                }
            }
            return $imgUrl;
        }
    }

    public function actionImageSort($item_id){
        $item = Item::find()->where(['owner_id' => \Yii::$app->user->id])->one();
        if($item == null){
            throw new NotFoundHttpException("Item not found, or not yours.");
        }

        $input = explode(",",\Yii::$app->request->post('order'));
        if (is_array($input)) {
            foreach ($input as $index => $mediaId) {
                $ihm = ItemHasMedia::find()->where([
                    'item_id' => $item_id,
                    'media_id' => $mediaId
                ])->one();
                if($ihm == null){
                    throw new BadRequestHttpException("Media / item combination not found.");
                }
                $ihm->order = $index+1;
                $ihm->save();
            }

            // @todo there is a weird bug here that requires it to be run twice, otherwise the order becomes (n,n+1....)
            // with n being number of items the first time it is changed instead of (1,2,3...). Should be debugged at
            // some point but this was quicker
            foreach ($input as $index => $mediaId) {
                $ihm = ItemHasMedia::find()->where([
                    'item_id' => $item_id,
                    'media_id' => $mediaId
                ])->one();
                if($ihm == null){
                    throw new BadRequestHttpException("Media / item combination not found.");
                }
                $ihm->order = $index+1;
                $ihm->save();
            }

            if($item->is_available == 1){
                (new ItemSearchDb())->sync([$item]);
            }
        }
        return ['success' => true];
    }
}