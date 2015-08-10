<?php

namespace app\controllers;

use app\modules\mail\models\MailMessage;
use Yii;
use yii\filters\VerbFilter;

class SendgridController extends Controller
{
    public $enableCsrfValidation = false;
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
