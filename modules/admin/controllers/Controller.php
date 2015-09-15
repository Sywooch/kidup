<?php
namespace app\modules\admin\controllers;

use app\modules\search\models\IpLocation;
use app\modules\user\models\Profile;
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
            throw new ForbiddenHttpException();
            return $this->redirect('@web/home');
        }
        return false;
    }

}