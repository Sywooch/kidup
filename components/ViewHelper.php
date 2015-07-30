<?php

namespace app\components;

class ViewHelper
{
    public static function getPageTitle($title){

        return html_entity_decode(ucfirst($title)) . ' - ' . \Yii::$app->name;
    }
}