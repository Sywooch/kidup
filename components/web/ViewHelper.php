<?php

namespace app\components\web;

use admin\models\TrackingEvent;

class ViewHelper
{
    public static function getPageTitle($title)
    {
        return html_entity_decode(ucfirst($title)) . ' - ' . \Yii::$app->name;
    }

    public static function trackPageView()
    {
        $action = @\Yii::$app->request->getUrl();
        if (!$action) {
            return '';
        }
        $action = urlencode($action);
        return "<script>kidupTracker('page_view', '{$action}')</script>";
    }

    public static function trackClick($type, $data = null, $wrapOnClick = true)
    {
        if (TrackingEvent::checkType($type)) {
            $tracker = "kidupTracker('{$type}','{$data}')";
            if(!$wrapOnClick){
                return $tracker;
            }
            return "onclick=\"{$tracker}\"";
        } else {
            return '';
        }
    }
}