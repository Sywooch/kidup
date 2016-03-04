<?php
namespace notification\models;

use notification\components\MailRenderer;
use notification\components\PushRenderer;
use user\models\User;

class TemplateRenderer {

    public $template;
    public $mailRenderer;
    public $pushRenderer;

    public function __construct() {
        $this->mailRenderer = new MailRenderer();
        $this->pushRenderer = new PushRenderer();
    }

    public function renderMail() {
        return $this->mailRenderer->render($this->template);
    }

    public function renderPush() {
        return $this->pushRenderer->render($this->template);
    }

}