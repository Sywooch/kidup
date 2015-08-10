<?php

namespace app\controllers;

use app\modules\mail\models\MailMessage;
use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\HttpException;

class SendgridController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'webhook-apqcbec' => ['post'],
                ],
            ],
        ];
    }

    public function actionWebhookApqcbec(){
        (new MailMessage())->processSendgridInput($_POST);
    }
}
