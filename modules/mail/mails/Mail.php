<?php
namespace mail\mails;

use mail\models\MailLog;
use mail\models\UrlFactory;
use Yii;
use yii\base\Object;
use yii\helpers\Json;

/**
 * An abstract mail class, which can be extended. This defined how a mail should look like and which data a mail should
 * contain.
 *
 * @package mail\mails
 */
abstract class Mail extends Object
{

    // E-mail address of the receiver
    public $emailAddress;

    // The username of the receiver
    public $userName;

    // Subject of the mail
    public $subject;

    // ID of the mail
    public $mailId;

    // The URL to views this mail in the browser
    public $seeInBrowserUrl;

    // The URL to the webpage which allows the user to change the e-mail settings
    public $changeSettingsUrl;

    // The template ID of the mail
    public $templateId;

    // The template of the mail
    private $template;

    // The e-mail address of the sender
    private $sender;

    // The name of the sender
    private $senderName;

    // The path to the views of all the mails
    private $viewPath;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $sender = (new \mail\components\MailUserFactory())->create('KidUp', 'info@kidup.dk');
        $this->setSender($sender);
        $this->viewPath = '@app/modules/mail/views';
        $this->mailId = MailLog::getUniqueId();
        $this->seeInBrowserUrl = UrlFactory::seeInBrowser($this->mailId);
        $this->changeSettingsUrl = UrlFactory::changeSettings();
    }

    /**
     * Find the template path of  mail.
     *
     * @return string The path of the template.
     */
    public function getTemplatePath()
    {
        if (isset($this->template)) {
            return $this->template;
        }
        $className = $this->className();
        $templatePath = str_replace("mail\\mails\\", '', $className);
        $templatePath = str_replace("\\", '/', $templatePath);
        return strtolower($templatePath . ".twig");
    }

    public static function getType() {
        return \mail\components\MailType::TYPE_NOT_DEFINED;
    }

    /**
     * Get the sender e-mail address.
     *
     * @return string E-mail address of the sender.
     */
    public function getSenderEmail()
    {
        return $this->sender;
    }

    /**
     * Get the name of the sender.
     *
     * @return string Name of the sender.
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * Get the path where the views of the mails are stored.
     *
     * @return string Path to the views of the mails.
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * Get the template ID of the mail.
     *
     * @return bool|int The template ID if the mail has one, false otherwise.
     */
    public function getTemplateId()
    {
        if (isset($this->templateId)) {
            return $this->templateId;
        }
        return false;
    }

    /**
     * Set the receiver of the e-mail.
     *
     * @param \mail\components\MailUser $receiver
     */
    public function setReceiver(\mail\components\MailUser $receiver)
    {
        $this->emailAddress = $receiver->email;
        $this->userName = $receiver->name;
    }

    /**
     * Get the name of the receiver of the e-mail.
     *
     * @return str Name of the receiver.
     */
    public function getReceiverName()
    {
        return $this->userName;
    }

    /**
     * Get the e-mail address of the receiver of the e-mail.
     *
     * @return str E-mail address of the receiver.
     */
    public function getReceiverEmail()
    {
        return $this->emailAddress;
    }

    /**
     * Set the sender of the e-mail.
     *
     * @param \mail\components\MailUser $sender
     */
    public function setSender(\mail\components\MailUser $sender)
    {
        $this->sender = $sender->email;
        $this->senderName = $sender->name;
    }

    /**
     * Subject getter.
     *
     * @return str The subject.
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Subject setter.
     *
     * @param str $subject The subject.
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getData() {
        return json::encode($this);
    }

    public function loadData($data) {
        $decoded = json::decode($data);
        foreach ($decoded as $key => $value) {
            $this->$key = $value;
        }
    }

}