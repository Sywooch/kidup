<?php

namespace app\modules\admin\controllers;

use app\controllers\Controller;
use app\models\base\User;
use Yii;

class DefaultController extends Controller
{

    public function actionIndex()
    {
        $this->checkAdmin();

        return $this->render('index', [
            'userCount' => User::find()->count()
        ]);
    }

    private function checkAdmin()
    {
        if (\Yii::$app->user->isGuest || !\Yii::$app->user->identity->isAdmin()) {
            return $this->redirect('@web/home');
        }
        return false;
    }
}
