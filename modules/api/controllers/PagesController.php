<?php
namespace api\controllers;

use app\extended\web\Controller;
use pages\helpers\Pages;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PagesController extends Controller
{
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