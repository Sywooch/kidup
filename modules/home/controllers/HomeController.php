<?php

namespace app\modules\home\controllers;

use app\components\Cache;
use app\controllers\Controller;
use app\modules\item\models\Category;
use app\modules\item\models\Item;
use Yii;

class HomeController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    // allow all users
                    [
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->transparentNav = true;
        $this->noContainer = true;

        return Cache::data('home.render', function () {
            $categories = Yii::$app->db->cache(function () {
                return Category::find()->all();
            });
            $items = Yii::$app->db->cache(function () {
                return Item::find()->limit(3)->orderBy('RAND()')->where(['is_available' => 1])->all();
            });

            return $this->render('index', [
                'categories' => $categories,
                'items' => $items,
            ]);
        }, 1);
    }
}
