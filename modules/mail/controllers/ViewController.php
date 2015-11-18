<?php

namespace mail\controllers;

use app\extended\web\Controller;
use booking\models\Booking;
use mail\mails\bookingOwner\PayoutFactory;
use mail\mails\bookingOwner\RequestFactory;
use mail\mails\bookingOwner\StartFactory;
use mail\mails\bookingOwner\FailedFactory;
use mail\mails\bookingOwner\ConfirmationFactory;
use mail\mails\MailRenderer;
use mail\mails\MailSender;
use mail\mails\user\ReconfirmFactory;
use mail\mails\user\ReconfirmInterface;
use mail\mails\user\WelcomeFactory;
use \mail\models\Mailer;
use \mail\models\MailLog;
use mail\widgets\Button;
use user\models\User;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;


class ViewController extends Controller
{
    public function actionIndex($id)
    {
        $mailLog = MailLog::findOne($id);
        if ($mailLog == null) {
        throw new NotFoundHttpException("Email not found");
    }

        $view = '/' . Mailer::getView($mailLog->type);
        $this->layout = '@mail/views/layouts/html';
        \Yii::$app->params['tmp_email_params'] = Json::decode($mailLog->data);

        return $this->render($view, Json::decode($mailLog->data));
    }

    public function actionLink($url, $mailId)
    {
        $url = base64_decode($url);
        preg_match("/http:\/\/(.*).kidup.dk/", $url, $output_array); // check if actually a kidup URL
        preg_match("/https:\/\/(.*).kidup.dk/", $url, $output_array_https); // check if actually a kidup URL

        return $this->redirect($url);
    }

    public function actionTest()
    {
        $factory = new ConfirmationFactory();

        // define many objects to play with
        $user = User::find()->one();
        $booking = Booking::find()->one();

        $mail = $factory->create($booking);
        return new MailRenderer($mail);
    }

}

