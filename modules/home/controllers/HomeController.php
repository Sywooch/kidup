<?php

namespace app\modules\home\controllers;

use app\components\Cache;
use app\controllers\Controller;
use app\modules\item\models\Category;
use app\modules\item\models\Item;
use Yii;
use yii\helpers\Json;

class HomeController extends Controller
{
    public function behaviors()
    {
        return [
            // access control, everybody can view this
            [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
            [
                'class' => 'yii\filters\HttpCache',
                'only' => ['index'],
                'cacheControlHeader' => 'public, max-age=300',
                'enabled' => YII_CACHE,
                'etagSeed' => function ($action, $params) {
                    return Json::encode([
                        Yii::$app->language,
                        \Yii::$app->session->getAllFlashes()
                    ]);
                },
            ],
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 60 * 20,
                'enabled' => YII_CACHE,
                'variations' => [
                    \Yii::$app->language,
                    \Yii::$app->session->getAllFlashes()
                ],
            ],
        ];
    }

    /**
     * Home page controller function
     * @return mixed
     */
    public function actionIndex()
    {
        $this->transparentNav = true;
        $this->noContainer = true;

        $categories = Yii::$app->db->cache(function () {
            return Category::find()->all();
        }, 24 * 3600);
        $items = Yii::$app->db->cache(function () {
            return Item::find()->limit(3)->orderBy('RAND()')->where(['is_available' => 1])->all();
        }, 6 * 3600);

        $res = $this->render('index', [
            'categories' => $categories,
            'items' => $items,
        ]);

        return $res;
    }
}
