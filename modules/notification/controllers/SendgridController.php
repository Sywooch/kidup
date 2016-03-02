<?php

namespace notification\controllers;

use notifications\models\MailMessage;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

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
