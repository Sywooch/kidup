<?php
namespace mail\controllers;

use app\extended\web\Controller;
use mail\mails\bookingRenter\PayoutFactory;
use mail\mails\MailRenderer;
use mail\mails\MailSender;
use mail\mails\user\ReconfirmInterface;
use mail\components;
use mail\models\MailLog;
use mail\widgets\Button;
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
        \Yii::setAlias('@mail', '@app/modules/mail');
        \Yii::setAlias('@mail-layouts', '@mail/views/layouts/');
        \Yii::setAlias('@mail-assets', '@mail/assets/');

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

