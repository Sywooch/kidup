<?php
namespace api\controllers;

use app\extended\web\Controller;
use pages\helpers\Pages;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PagesController extends Controller
{
    public function behaviors()
    {

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
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ]);
    }

    public function actionView($page)
    {
        $p = (new Pages())->get($page);
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if (isset($p['content'])) {
            return $p;
        }
        throw new NotFoundHttpException("Page not found");
    }

}