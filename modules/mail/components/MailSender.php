<?php
namespace mail\components;

class MailSender
{

    /**
     * Send a mail.
     *
     * @param \mail\mails\Mail $mail Mail to send.
     * @return bool Whether the mail was sent succesfully.
     */
    public static function send($mail)
    {
        $renderer = new \mail\components\MailRenderer($mail);
        $view = $renderer->render();
        /** @var \yii\swiftmailer\Mailer $mailer */
        $mailer = \Yii::$app->mailer;
        $mailer->getView()->theme = \Yii::$app->view->theme;

        $logEntry = \mail\models\MailLog::create(MailType::getType($mail), $mail->getReceiverEmail(),
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
    }

}