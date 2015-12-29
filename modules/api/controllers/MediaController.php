<?php
namespace api\controllers;

use api\models\Item;
use api\models\Media;
use api\models\Review;
use images\components\ImageManager;
use item\controllers\CreateController;
use search\forms\Filter;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\NotAcceptableHttpException;
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
            'user' => ['create', 'delete', 'index']
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
        if($profile_pic !== false){
            $img = UploadedFile::getInstanceByName('file');
            $p = \Yii::$app->user->identity->profile;
            $p->setAttribute('img', (new ImageManager())->upload($img));
            if($p->save()){
                return true;
            }else{
                return $p->getErrors();
            }
        }else if($item_id !== null){
            $item = Item::find()->where(['id' => $item_id])->one();
            if($item == null){
                throw new NotFoundHttpException("Item not found");
            }
            /*
             * @var $item Item
             */
            if(!$item->hasModifyRights()){
                throw new ForbiddenHttpException();
            }
            if(\Yii::$app->runAction('item/create/upload',['item_id' => $item_id]) == true){
                $media = Media::find()->where(['user_id' => \Yii::$app->user->id])->orderBy('created_at DESC')->one();
                return $media;
            }
        }
        return false;
    }
}