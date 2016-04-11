<?php

namespace codecept\_support;

use AcceptanceTester;
use codecept\muffins\OauthAccessTokenMuffin;
use Codeception\Util\Debug;
use FunctionalTester;
use notification\models\NotificationMailClickLog;
use notification\models\NotificationMailLog;
use notification\models\NotificationPushLog;
use user\models\user\User;
use Yii;
use yii\helpers\ArrayHelper;

class NotificationHelper
{

    public static function getMail()
    {
        $view = null;
        $params = null;
        $received = false;
        $viewpath = \Yii::$aliases['@runtime'] . '/notification/mail.view';
        if (file_exists($viewpath)) {
            $received = true;
            $view = file_get_contents($viewpath);
        }
        $paramspath = \Yii::$aliases['@runtime'] . '/notification/mail.params';
        if (file_exists($paramspath)) {
            $params = json_decode(file_get_contents($paramspath), true);
        }
        return [$received, $view, $params];
    }

    public static function getPush()
    {
        $view = null;
        $params = null;
        $received = false;
        $viewpath = \Yii::$aliases['@runtime'] . '/notification/push.view';
        if (file_exists($viewpath)) {
            $received = true;
            $view = file_get_contents($viewpath);
        }
        $paramspath = \Yii::$aliases['@runtime'] . '/notification/push.params';
        if (file_exists($paramspath)) {
            $params = json_decode(file_get_contents($paramspath), true);
        }
        return [$received, $view, $params];
    }

    public static function emptyNotifications()
    {
        // Empty notifications
        NotificationMailLog::deleteAll();
        NotificationMailClickLog::deleteAll();
        NotificationPushLog::deleteAll();
        $path = \Yii::$aliases['@runtime'] . '/notification/*';
        foreach (glob($path) as $file) {
            unlink($file);
        }
    }

}
