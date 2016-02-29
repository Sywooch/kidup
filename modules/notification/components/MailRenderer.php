<?php
namespace notification\components;

use Yii;

class MailRenderer extends Renderer
{

    protected static $templateFolder = '@notification-mail';

    public static function render($template, $title = '', $vars = []) {
        $vars['title'] = $title;
        return \Yii::$app->view->renderFile(self::$templateFolder . '/' . $template . '.twig', $vars);
    }

}