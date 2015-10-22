<?php
namespace admin\controllers;

use \search\models\IpLocation;
use \user\models\Profile;
use Yii;
use yii\web\ForbiddenHttpException;

class Controller extends \yii\web\Controller
{
    public function __construct($id, $controller)
    {
        $this->checkAdmin();
        $this->layout = '@app/modules/admin/views/layouts/admin';
        return parent::__construct($id, $controller);
    }

    private function checkAdmin()
    {
        if (\Yii::$app->user->isGuest || !\Yii::$app->user->identity->isAdmin()) {
            throw new ForbiddenHttpException("You need admin privileges to get in here!");
        }
        return false;
    }

}