<?php
namespace notification\components;

use Yii;

class MailRenderer extends Renderer
{

    protected $templateFolder = '@notification-mail';

    public function render() {
        $html = $this->renderFromFile($this->template);
        $emogrifier = new \Pelago\Emogrifier();
        $emogrifier->setHtml($html);
        return $emogrifier->emogrify();
    }

}