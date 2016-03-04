<?php
namespace notification\components;

use Swift_Message;
use yii\swiftmailer\Mailer;

class MailSender
{

    /**
     * Send a mail.
     *
     * @param $renderer
     * @return bool Whether the mail was sent succesfully.
     */
    public static function send($renderer)
    {
        $view = $renderer->renderMail();

        /** @var \yii\swiftmailer\Mailer $mailer */
        $mailer = \Yii::$app->mailer->compose();
        echo $mailer
            ->setTo('kevin91nl@gmail.com')
            ->setReplyTo(['info@kidup.dk' => 'KidUp'])
            ->setFrom(['info@kidup.dk' => 'KidUp'])
            ->setSubject('Subject')
            ->setHtmlBody($view)
            ->send()
        ;

        /*$view = $renderer->render();
        $mailer = \Yii::$app->mailer;
        $mailer->getView()->theme = \Yii::$app->view->theme;

        $logEntry = MailLog::create(MailType::getType($mail), $mail->getReceiverEmail(),
            $mail->getData(), $mail->mailId);

        if ($logEntry !== false) {
            return $mailer->compose()
                ->setTo($mail->getReceiverEmail())
                ->setReplyTo([$mail->getSenderEmail() => $mail->getSenderName()])
                ->setFrom(['info@kidup.dk' => $mail->getSenderName()])
                ->setSubject($mail->getSubject())
                ->setHtmlBody($view)
                ->send();
        } else {
            // No log entry was found
            return false;
        }
        */
    }

}