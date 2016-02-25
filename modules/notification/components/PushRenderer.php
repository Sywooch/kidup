<?php
namespace notification\components;

use Yii;

class PushRenderer extends Renderer
{

    protected $templateFolder = '@notification-push';

    public function render($template) {
        echo $template;
    }

}