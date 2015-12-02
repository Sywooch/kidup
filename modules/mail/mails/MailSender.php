<?php
namespace mail\mails;

class MailSender
{

    /** @var Mail Mail object. */
    private $mail;

    public function __construct(\mail\mails\Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Send the mail.
     */
    public function send()
    {
        $renderer = new \mail\mails\MailRenderer($this->mail);
        $view = $renderer->render();
        $mailer = \Yii::$app->mailer;
        $mailer->getView()->theme = \Yii::$app->view->theme;

        $logEntry = \mail\models\MailLog::create(MailType::getType($this->mail), $this->mail->getReceiverEmail(),
            $this->mail->getData(), $this->mail->mailId);

        if ($logEntry !== false) {
            return $mailer->compose()
                ->setTo($this->mail->getReceiverEmail())
                ->setReplyTo([$this->mail->getSenderEmail() => $this->mail->getSenderName()])
                ->setFrom(['info@kidup.dk' => $this->mail->getSenderName()])
                ->setSubject($this->mail->getSubject())
                ->setHtmlBody($view)
                ->send();
        }
    }

}