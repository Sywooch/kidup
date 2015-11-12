<?php
namespace mail\mails;

use mail\components\MailUrl;
use mail\models\MailLog;
use mail\models\UrlFactory;
use user\models\User;
use Yii;
use yii\base\Object;
use yii\helpers\Json;

abstract class Mail extends Object
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
    public $templateId;

    public function __construct($config = []){
        parent::__construct($config);
        $sender = (new MailUserFactory())->create('info@kidup.dk', 'KidUp');
        $this->setSender($sender);
        $this->viewPath = '@app/modules/mail/views';
        $this->mailId = MailLog::getUniqueId();
        $this->seeInBrowserUrl = UrlFactory::seeInBrowser($this->mailId);
        $this->changeSettingsUrl = UrlFactory::changeSettings();
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

    public function setSender($sender){
        $this->sender = $sender;
    }

    public function getSenderName(){
        return $this->senderName;
    }

    public function getViewPath(){
        return $this->viewPath;
    }

    public function getTemplateId(){
        if(isset($this->templateId)){
            return $this->templateId;
        }
        return false;
    }

    /**
     * Set the receiver of the e-mail.
     *
     * @param MailUser $receiver
     */
    public function setReceiver(MailUser $receiver) {
        $this->emailAddress = $receiver->email;
        $this->userName = $receiver->name;
    }

    /**
     * Set the sender of the e-mail.
     *
     * @param MailUser $sender
     */
    public function setSender(MailUser $sender) {
        $this->sender = $sender->email;
        $this->senderName = $sender->name;
    }

}