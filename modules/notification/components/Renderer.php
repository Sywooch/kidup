<?php
namespace notification\components;

use Yii;

class Renderer
{

    protected static $templateFolder;

    public static function render($template) {
        echo $template;
    }

}