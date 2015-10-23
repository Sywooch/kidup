<?php
namespace api\controllers;

use yii\filters\AccessControl;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class Controller extends \yii\rest\ActiveController
{

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function accessControl(){
        return [];
    }

    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }

    public function afterAction($action, $result)
    {
        if(\Yii::$app->request->get('access-token') && in_array($action->controller->action, $this->accessControl())){
            // access-token is set, but not used by yii because it's a publicly available api point
            \Yii::$app->getUser()->loginByAccessToken(\Yii::$app->request->get('access-token'), get_class($this));
        }
        if(\Yii::$app->request->get('lang')){
            // access-token is set, but not used by yii because it's a publicly available api point
            \Yii::$app->language = \Yii::$app->request->get('lang');
        }
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
        $res = $this->accessControl();
        if(!isset($res['guest'])){
            throw new ServerErrorHttpException("Access control for guest should be defined!");
        }
        if(!isset($res['user'])){
            throw new ServerErrorHttpException("Access control for user should be defined!");
        }

        return ArrayHelper::merge(parent::behaviors(), [
            'cors' => ['class' => Cors::className()],
            'authenticator' => [
                'class' => QueryParamAuth::className(),
                'except' => $res['guest']
            ],
            'accessControl' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => $res['guest'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'actions' => $res['user'],
                        'roles' => ['@']
                    ],
                ],
            ],
        ]);
    }

}