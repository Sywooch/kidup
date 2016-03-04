<?php
namespace notification\controllers;

use app\extended\web\Controller;
use notification\components\MailRenderer;
use notification\components\MailSender;
use notification\models\template\UserWelcomeRenderer;
use user\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class ViewController extends Controller
{

    public $layout;

    public function actionIndex($id)
    {
        $mailLog = MailLog::findOne($id);
        if ($mailLog == null) {
            throw new NotFoundHttpException("Email not found");
        }

        $mail = \mail\mails\MailType::getModel($mailLog->type);
        $mail->loadData($mailLog->data);
        $renderer = new MailRenderer($mail);
        $renderer->render();
    }

    public function actionLink($url, $mailId)
    {
        return \Yii::$app->view->renderFile('@notification-layouts/link.twig', [
            'url' => base64_decode($url),
            'appstore_google_url' => Yii::$app->params['appstore-google'],
            'appstore_ios_url' => Yii::$app->params['appstore-ios'],
        ]);
    }

    public function actionTest() {
        //$renderer = new UserWelcomeRenderer(User::find()->where(['email' => 'kevin91nl@gmail.com'])->one());
        //MailSender::send($renderer);
        //return $renderer->renderMail();
    }

}

