<?php
namespace api\controllers;

use yii\filters\AccessControl;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class Controller extends \yii\rest\ActiveController
{

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }

    public function afterAction($action, $result)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::afterAction($action, $result);
    }

    public function beforeAction($action)
    {
        \Yii::$app->request->parsers[] = 'yii\web\JsonParser';
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'cors' => ['class' => Cors::className()],
            'authenticator' => [
                'class' => QueryParamAuth::className(),
            ],
        ]);
    }

}