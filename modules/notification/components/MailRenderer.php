<?php
namespace notification\components;

use Yii;

class MailRenderer extends Renderer
{

    protected $templateFolder = '@notification-mail';

    public function render($template, $title = '', $vars = []) {
        return $this->renderFromFile($template);
    }

}