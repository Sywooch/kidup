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
                'etagSeed' => function ($action, $params) {
                    return Json::encode([Yii::$app->language, \Yii::$app->user->id, \Yii::$app->session->getAllFlashes()]);
                },
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
        $res = Cache::html('home_controller-home-render', function () {

            $categories = Yii::$app->db->cache(function () {
                return Category::find()->all();
            }, 24*3600);
            $items = Yii::$app->db->cache(function () {
                return Item::find()->limit(3)->orderBy('RAND()')->where(['is_available' => 1])->all();
            }, 6*3600);

            $res = $this->render('index', [
                'categories' => $categories,
                'items' => $items,
            ]);

            return $res;
        }, ['duration' => 20*60, 'variations' => \Yii::$app->session->getAllFlashes()]);
        return $res;
    }
}
