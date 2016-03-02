<?php
namespace notification\controllers;

use app\extended\web\Controller;
use notifications\components;
use notifications\mails\bookingRenter\PayoutFactory;
use notifications\mails\MailRenderer;
use notifications\mails\MailSender;
use notifications\mails\user\ReconfirmInterface;
use notifications\models\MailLog;
use notifications\widgets\Button;
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
        $url = base64_decode($url);
        preg_match("/http:\/\/(.*).kidup.dk/", $url, $output_array); // check if actually a kidup URL
        preg_match("/https:\/\/(.*).kidup.dk/", $url, $output_array_https); // check if actually a kidup URL

        return $this->redirect($url);
    }

}

