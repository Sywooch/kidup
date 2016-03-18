<?php
namespace notification\components;

use notification\models\TemplateRenderer;
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
    public static function send(MailRenderer $renderer)
    {
        $view = $renderer->render();

        $replyTo = ['info@kidup.dk' => 'KidUp'];
        $from = ['info@kidup.dk' => 'KidUp'];
        $subject = 'KidUp';
        $receiverEmail = $renderer->getReceiverEmail();

        // Do some test magic
        if (\Yii::$app->modules['notification']->useFileTransfer) {
            $path = \Yii::$aliases['@runtime'] . '/notification/';
            if (!is_dir($path)) {
                mkdir($path);
            }
            file_put_contents($path . 'mail.view', $view);
            file_put_contents($path . 'mail.params', json_encode([
                'replyTo' => ['info@kidup.dk' => 'KidUp'],
                'from' => $from,
                'subject' => $subject,
                'receiverEmail' => $receiverEmail
            ]));
            return true;
        }

        /** @var \yii\swiftmailer\Mailer $mailer */
        $receiverEmail = 'kevin91nl@gmail.com';
        $mailer = \Yii::$app->mailer->compose();
        return $mailer
            ->setTo($receiverEmail)
            ->setReplyTo($replyTo)
            ->setFrom($from)
            ->setSubject($subject)
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