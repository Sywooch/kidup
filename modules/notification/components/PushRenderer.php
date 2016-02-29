<?php
namespace notification\components;

use Yii;

class PushRenderer extends Renderer
{

    protected $templateFolder = '@notification-push';

    public static function render($template) {
        echo $template;
    }

}