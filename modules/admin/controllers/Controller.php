<?php
namespace admin\controllers;

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
            \Yii::$app->session->addFlash("warning", "You need admin privileges to get in here!");
            \Yii::$app->response->redirect("@web/login");
            \Yii::$app->response->send();
            \Yii::$app->end();
        }
        return false;
    }

}