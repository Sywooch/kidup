<?php

namespace app\components;

use app\extended\base\Exception;

class PermissionException extends Exception
{
}

class Permission extends \yii\base\Component
{
    const ROOT = 'root';
    const ADMIN = 'admin';
    const OWNER = 'owner';
    const USER = 'user';
    const GUEST = 'guest';

    const ACTION_CREATE = 'create';
    const ACTION_READ = 'read';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    public static function isGuest()
    {
        return \Yii::$app->user->getIsGuest() || self::isUser();
    }

    public static function isAdmin()
    {
        return \Yii::$app->user->isAdmin() || self::isRoot();
    }

    public static function isUser()
    {
        return !\Yii::$app->user->getIsGuest() || self::isAdmin();
    }

    public static function isRoot()
    {
        return YII_CONSOLE || !\Yii::$app->user->isRoot();
    }

}