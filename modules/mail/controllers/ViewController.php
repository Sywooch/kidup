<?php

namespace app\modules\mail\controllers;

use app\components\Event;
use app\controllers\Controller;
use app\models\base\Booking;
use app\modules\booking\models\Payin;
use app\modules\item\models\Item;
use app\modules\mail\models\Mailer;
use app\modules\mail\models\MailLog;
use app\modules\mail\models\MailMessage;
use app\modules\message\models\Message;
use app\modules\user\models\User;
use Yii;
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
        if (count($output_array) == 0 && !YII_DEBUG) {
            throw new ServerErrorHttpException("You're being spoofed");
        }

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
//        $u = \app\modules\booking\models\Booking::find()->orderBy('id DESC')->one();
//        Event::trigger($u, \app\modules\booking\models\Booking::EVENT_BOOKING_ALMOST_START);
//
//        // confirmations, renter receipt
//        $u = Payin::find()->orderBy('id DESC')->one();
//        Event::trigger($u, Payin::EVENT_PAYIN_CONFIRMED);
//        $m = (new MailMessage())->parseInbox();
//
//        $u = \app\modules\booking\models\Booking::find()->orderBy('id DESC')->one();
//        Event::trigger($u, \app\modules\booking\models\Booking::EVENT_REVIEWS_PUBLIC);
        $u = Payin::find()->orderBy('id DESC')->one();
        Event::trigger($u, Payin::EVENT_FAILED);
    }
}

