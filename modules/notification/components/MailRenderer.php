<?php
namespace notification\components;

use Yii;

class MailRenderer extends Renderer
{

    protected $templateFolder = '@notification-mail';

    public function render($template) {
        if ($template === null) $template = $this->template;
        $html = $this->renderFromFile($template);
        $emogrifier = new \Pelago\Emogrifier();
        $emogrifier->setHtml($html);
        return $emogrifier->emogrify();
    }

}