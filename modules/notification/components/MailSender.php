<?php
namespace notification\components;

use notification\models\MailLog;
use notification\models\NotificationMailLog;
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
        
        // Log it
        $log = new NotificationMailLog();
        $log->template = $renderer->getTemplate();
        $log->receiver_id = $renderer->getReceiverId();
        $log->to = $receiverEmail;
        $log->reply_to = json_encode($replyTo);
        $log->from = json_encode($from);
        $log->subject = $subject;
        $log->variables = json_encode($renderer->getVariables());
        $log->view = $view;
        $log->save();

        // Do some test magic
        if (\Yii::$app->modules['notification']->useFileTransfer) {
            $path = \Yii::$aliases['@runtime'] . '/notification/';
            if (!is_dir($path)) {
                mkdir($path);
            }
            file_put_contents($path . 'mail.view', $view);
            file_put_contents($path . 'mail.params', json_encode([
                'replyTo' => $replyTo,
                'from' => $from,
                'subject' => $subject,
                'receiverEmail' => $receiverEmail
            ]));
            return true;
        }

        /** @var \yii\swiftmailer\Mailer $mailer */
        $mailer = \Yii::$app->mailer->compose();
        return $mailer
            ->setTo($receiverEmail)
            ->setReplyTo($replyTo)
            ->setFrom($from)
            ->setSubject($subject)
            ->setHtmlBody($view)
            ->send()
        ;
    }

}