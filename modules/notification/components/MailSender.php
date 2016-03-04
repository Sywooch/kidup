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
        $view = '123';

        $message = Swift_Message::newInstance();
        $message
            ->setTo('kevin91nl@gmail.com')
            ->setReplyTo(['info@kidup.dk' => 'KidUp'])
            ->setFrom(['info@kidup.dk' => 'KidUp'])
            ->setSubject('Subject')
            ->setBody($view, 'text/html')
        ;

        /** @var \yii\swiftmailer\Mailer $mailer */
        $mailer = new Mailer();
        $failures = [];
        $result = $mailer->send($message, $failures);
        var_dump($failures);

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