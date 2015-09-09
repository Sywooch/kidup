<?php

namespace app\modules\admin\controllers;

use app\controllers\Controller;
use app\models\base\User;
use app\modules\item\models\Item;
use Yii;

class DefaultController extends Controller
{

    /**
     * Admin Dasbhboard
     */
    public function actionIndex()
    {
        $this->checkAdmin();

        $userData = ['Users'];
        for($i = 30; $i >= 0; $i--){
            $userData[] = static::userCount($i);
        }

        $itemData = ['Items'];
        for($i = 30; $i >= 0; $i--){
            $itemData[] = static::itemCount($i);
        }

        return $this->render('index', [
            'userData' => [ $userData],
            'itemData' => [ $itemData],
        ]);
    }

    private static function userCount($daysAgo){
        return User::find()->where('created_at <= :date')->params([':date' => time()-$daysAgo*24*60*60])->count();
    }

    private static function itemCount($daysAgo){
        return Item::find()->where('created_at <= :date')->params([':date' => time()-$daysAgo*24*60*60])->count();
    }

    private function checkAdmin()
    {
        if (\Yii::$app->user->isGuest || !\Yii::$app->user->identity->isAdmin()) {
            return $this->redirect('@web/home');
        }
        return false;
    }
}
