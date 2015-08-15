<?php

namespace app\modules\home\controllers;

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

        $categories = Category::find()->all();
        $items = Item::find()->limit(3)->orderBy('RAND()')->where(['is_available' => 1])->all();

        return $this->render('index', [
            'categories' => $categories,
            'items' => $items,
        ]);
    }
}
