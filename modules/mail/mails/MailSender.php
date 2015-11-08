<?php
namespace mail\mails;

use mail\components\MailUrl;
use mail\models\Mailer;
use mail\models\MailLog;
use user\models\User;
use Yii;
use yii\helpers\Json;

class MailSender
{
    private $mail;

    public function __construct(Mail $mail){
        $this->mail = $mail;
        $this->send();
    }

    private function send()
    {
        $mailer = \Yii::$app->mailer;
        $mailer->viewPath = $this->mail->getViewPath();
        $viewModel = Json::decode(Json::encode($this->mail));
        $mailer->htmlLayout = '@mail/views/layouts/html.twig';
//
//        $params['receivingProfile'] = Profile::find()->where(['user_id' => User::findOne(['email' => $data['email']])->id])
//            ->select('first_name')->asArray()->one();
//        $params['urls']['mailInBrowser'] = MailUrl::to(Url::to('@web/mail/' . $logId, true), $logId);
//        $params['urls']['changeSettings'] = MailUrl::to(Url::to('@web/user/settings/profile', true), $logId);
//
//        if (isset($data['sender'])) {
//            $this->sender = $data['sender'];
//        }
//        if (isset($data['sender_name'])) {
//            $this->senderName = $data['senderName'] . ' (KidUp)';
//        }
//
//        $log = MailLog::create($data['type'], $data['email'], $params, $logId);
//
//        $view = self::getView($data['type']);
//        \Yii::$app->params['tmp_email_params'] = $params;
        (new Mailer())->registerWidgets();
        $renderedTemplate = \Yii::$app->view->renderFile($this->mail->getViewPath().'/'.$this->mail->getTemplatePath(),['vm' => $viewModel]);
        $renderedLayout = \Yii::$app->view->renderFile($this->mail->getViewPath().'/layouts/html.twig',['content' => $renderedTemplate]);
        return $mailer->compose()
            ->setTo($this->mail->emailAddress)
            ->setReplyTo([$this->mail->getSender() => $this->mail->getSenderName()])
            ->setFrom(['info@kidup.dk' => $this->mail->getSenderName()])
            ->setSubject($this->mail->subject)
            ->setHtmlBody($renderedLayout)
            ->send();
    }
}