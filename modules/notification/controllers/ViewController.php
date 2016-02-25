<?php
namespace notification\controllers;

use app\extended\web\Controller;
use notifications\mails\bookingRenter\PayoutFactory;
use notifications\mails\MailRenderer;
use notifications\mails\MailSender;
use notifications\mails\user\ReconfirmInterface;
use notifications\components;
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

    public function actionTest($mail)
    {
        \Yii::setAlias('@notification', '@app/modules/mail');
        \Yii::setAlias('@notification-layouts', '@notification/views/layouts/');
        \Yii::setAlias('@notification-assets', '@notification/assets/');

        $mails = [
            'booking_owner_confirmation' => ['user']
        ];

        if (array_key_exists($mail, $mails)) {
            $vars = [];
            foreach ($mails[$mail] as $var) {
                $vars[$var] = '<b style="color: red;">[' . $var .']</b>';
            }
            echo \Yii::$app->view->renderFile('@mail-layouts/' . $mail . '.twig', $vars);
        } else {

            /*echo \Yii::$app->view->renderFile('@mail/views/system/overview.twig', [
                'mails' => $mails
            ]);*/
            return 123;
        }
    }

    public function actionOverview() {
        $this->layout = '@app/modules/admin/views/layouts/admin123';
        return \Yii::$app->view->render('@mail/views/system/overview', [
            'mails' => 123
        ]);
    }

    /*
     * $factory = new NewMessageFactory();

        $mail->setReceiver((new \mail\components\MailUserFactory())->create('Example receiver', 'receiver@kidup.dk'));
        $renderer = new \mail\components\MailRenderer($mail);
        echo $renderer->render();
        //\mail\components\MailSender()::send($mail);

        //return new MailRenderer($mail);
     */

}

