<?php
namespace mail\mails;

use mail\components\MailUrl;
use mail\models\MailLog;
use user\models\User;
use Yii;
use yii\helpers\Json;

// @todo split up
abstract class MailFactory
{
    public $template;
    public $emailAddress;
    public $subject;

    public $mailId;

    private $sender;
    private $senderName;
    private $viewPath;

    public function __construct()
    {
        $this->sender = 'info@kidup.dk';
        $this->senderName = 'KidUp';
        $this->viewPath = '@app/modules/mail/views';
        $this->mailId = MailLog::getUniqueId();
    }

    public function getUserName()
    {
        $user = User::findOne(['email' => $this->emailAddress]);
        return $user->profile->getUserName();
    }

    public function sendMessage($data)
    {
        $mailer = \Yii::$app->mailer;
        $mailer->viewPath = $this->viewPath;
        $mailer->getView()->theme = \Yii::$app->view->theme;

        $params = Json::decode(Json::encode($data['params']));
        foreach ($data['urls'] as $name => $url) {
            $params['urls'][$name] = MailUrl::to($url, $logId);
        }

        $params['receivingProfile'] = Profile::find()->where(['user_id' => User::findOne(['email' => $data['email']])->id])
            ->select('first_name')->asArray()->one();
        $params['urls']['mailInBrowser'] = MailUrl::to(Url::to('@web/mail/' . $logId, true), $logId);
        $params['urls']['changeSettings'] = MailUrl::to(Url::to('@web/user/settings/profile', true), $logId);

        if (isset($data['sender'])) {
            $this->sender = $data['sender'];
        }
        if (isset($data['sender_name'])) {
            $this->senderName = $data['senderName'] . ' (KidUp)';
        }

        $log = MailLog::create($data['type'], $data['email'], $params, $logId);

        $view = self::getView($data['type']);
        \Yii::$app->params['tmp_email_params'] = $params;
        return $mailer->compose($view, $params)
            ->setTo($data['email'])
            ->setReplyTo([$this->sender => $this->senderName])
            ->setFrom(['info@kidup.dk' => $this->senderName])
            ->setSubject($data['subject'])
            ->send();
    }
}