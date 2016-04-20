<?php
namespace api\v1\controllers;

use app\components\Exception;
use yii\filters\AccessControl;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class Controller extends \yii\rest\ActiveController
{

    public $relationalQuery;

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function accessControl()
    {
        return [];
    }


    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;

        // log in users manually if they set an access token
        if (\Yii::$app->request->get('access-token')) {
            try{
                \Yii::$app->getUser()->loginByAccessToken(\Yii::$app->request->get('access-token'), get_class($this));
            }catch(Exception $e){
                \Yii::$app->response->data = $e;
                \Yii::$app->end();
            }
        }
        \Yii::$app->language = 'da-DK';
        if (\Yii::$app->request->get('lang')) {
            // access-token is set, but not used by yii because it's a publicly available api point
            \Yii::$app->language = \Yii::$app->request->get('lang');
        }
        if (!\Yii::$app->user->isGuest && \Yii::$app->user->identity->profile->language !== null) {
            \Yii::$app->language = \Yii::$app->user->identity->profile->language;
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }

    public function behaviors()
    {
        $res = $this->accessControl();
        if (!isset($res['guest'])) {
            throw new ServerErrorHttpException("Access control for guest should be defined!");
        }
        if (!isset($res['user'])) {
            throw new ServerErrorHttpException("Access control for user should be defined!");
        }
        if (!in_array('options', $res['guest'])) {
            $res['guest'][] = 'options';
        }
        if (!in_array('options', $res['user'])) {
            $res['user'][] = 'options';
        }

        return ArrayHelper::merge(parent::behaviors(), [
            'cors' => [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Allow-Origin' => '*',
                ],
            ],
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
                        'actions' => array_merge($res['guest'], $res['user']),
                        'roles' => ['@']
                    ],
                ],
            ],
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ]);
    }

}