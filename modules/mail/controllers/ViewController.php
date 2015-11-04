<?php

namespace mail\controllers;

use app\extended\web\Controller;
use app\helpers\Event;
use booking\models\Payin;
use mail\models\Mailer;
use mail\models\MailLog;
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
        // item reminder
//        $u = Item::find()->orderBy('id DESC')->one();
//        Event::trigger($u, Item::EVENT_UNFINISHED_REMINDER);
//
//        // user login
//         $u = User::find()->orderBy('id DESC')->one();
//         Event::trigger($u, User::EVENT_USER_REGISTER_DONE);
//
//        // booking confirm
//         $u = Payin::find()->orderBy('id DESC')->one();
//         Event::trigger($u, Payin::EVENT_AUTHORIZATION_CONFIRMED);
//
//        // new message
//        $u = Message::find()->orderBy('id DESC')->one();
//        Event::trigger($u, Message::EVENT_NEW_MESSAGE);
//
//        // about to start
//        $u = \booking\models\Booking::find()->orderBy('id DESC')->one();
//        Event::trigger($u, \booking\models\Booking::EVENT_BOOKING_ALMOST_START);
//
//        // confirmations, renter receipt
//        $u = Payin::find()->orderBy('id DESC')->one();
//        Event::trigger($u, Payin::EVENT_PAYIN_CONFIRMED);
//        $m = (new MailMessage())->parseInbox();
//
//        $u = \booking\models\Booking::find()->orderBy('id DESC')->one();
//        Event::trigger($u, \booking\models\Booking::EVENT_REVIEWS_PUBLIC);
        $u = Payin::find()->orderBy('id DESC')->one();
        Event::trigger($u, Payin::EVENT_FAILED);
    }
}

