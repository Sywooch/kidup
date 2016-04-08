<?php
namespace notification\components;

use Yii;

class MailRenderer extends Renderer
{

    public $type = 'mail';

    protected $templateFolder = '@notification-mail';

    public function render($template = null)
    {
        if ($template === null) {
            $template = $this->template;
        }
        $html = $this->renderFromFile($template);
        $emogrifier = new \Pelago\Emogrifier();
        $emogrifier->setHtml($html);
        return $emogrifier->emogrify();
    }

}