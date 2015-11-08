<?php
namespace mail\mails;

use mail\components\MailUrl;
use mail\models\MailLog;
use mail\models\UrlFactory;
use user\models\User;
use Yii;
use yii\base\Object;
use yii\helpers\Json;

abstract class Mail extends Object implements MailInterface
{
    public $emailAddress;
    public $subject;
    public $mailId;
    public $seeInBrowserUrl;
    public $changeSettingsUrl;
    public $userName;

    private $template;
    private $sender;
    private $senderName;
    private $viewPath;

    public function __construct($config = []){
        parent::__construct($config);
        $this->sender = 'info@kidup.dk';
        $this->senderName = 'KidUp';
        $this->viewPath = '@app/modules/mail/views';
        $this->mailId = MailLog::getUniqueId();
        $this->seeInBrowserUrl = UrlFactory::seeInBrowser($this->mailId);
        $this->changeSettingsUrl = UrlFactory::changeSettings();
        $this->userName = $this->getUserName();
    }

    private function getUserName(){
        $user = User::findOne(['email' => $this->emailAddress]);
        return $user->profile->getUserName();
    }
    
    public function getTemplatePath(){
        if(isset($this->template)){
            return $this->template;
        }
        $className = $this->className();
        $templatePath = str_replace("mail\\mails\\", '', $className);
        $templatePath = str_replace("\\", '/', $templatePath);
        return strtolower($templatePath.".twig");
    }

    public function getSender(){
        return $this->sender;
    }

    public function getSenderName(){
        return $this->senderName;
    }

    public function getViewPath(){
        return $this->viewPath;
    }
}