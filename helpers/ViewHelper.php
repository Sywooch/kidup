<?php

namespace app\helpers;

use admin\models\TrackingEvent;

class ViewHelper
{
    public static function getPageTitle($title)
    {
        return html_entity_decode(ucfirst($title)) . ' - ' . \Yii::$app->name;
    }

    public static function trackPageView()
    {
        $action = @\Yii::$app->controller->getRoute();
        if (!$action) {
            return '';
        }
        $action = str_replace("/", "-", $action);
        $params = @\Yii::$app->controller->actionParams;
        $ps = [];
        foreach ($params as $id => $param) {
            if (strpos($id, '[') === false) {
                $ps[] = $id . "-" . $param;
            }
        }

        $action .= "." . implode("|", $ps);
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