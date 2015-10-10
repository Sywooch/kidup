<?php

namespace home\controllers;

use app\components\Cache;
use app\extended\web\Controller;
use \home\forms\Search;
use \item\models\Category;
use \item\models\Item;
use Yii;
use yii\helpers\ArrayHelper;
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

        $searchModel = new Search();

        $categories = Yii::$app->db->cache(function () {
            return Category::find()->indexBy('name')->all();
        }, 24 * 3600);
        $items = Yii::$app->db->cache(function () {
            // get 4 with review, or callback if not 4
            $items = Item::find()->limit(4)->orderBy('RAND()')->where(['is_available' => 1])->innerJoinWith('reviews')->all();
            if(count($items) < 4){
                $items = ArrayHelper::merge($items, Item::find()->limit(4-count($items))->orderBy('RAND()')->where(['is_available' => 1])->all());
            }
            return $items;
        }, 6 * 3600);

        
        $res = $this->render('index', [
            'categories' => $categories,
            'items' => $items,
            'searchModel' => $searchModel
        ]);

        return $res;
    }

    public function actionChangeLanguage($lang){
        $l = \user\models\Language::findOne($lang);

        if($l !== null){
            if(!\Yii::$app->user->isGuest){
                $u = (new \DeepCopy\DeepCopy())->copy(\Yii::$app->user->identity->profile);
                $u->language = $lang;
                $u->save();
            }
            Yii::$app->session->set('lang', $lang);
            Yii::$app->session->close(); // forces a write to the db, not done by default because it returns a redirect, not a page
        }else{
            Yii::error('Language undefined: '.$lang);
        }

        if(isset($_SERVER["HTTP_REFERER"])){
            return $this->redirect($_SERVER["HTTP_REFERER"]);
        }else{
            return $this->goHome();
        }
    }

    public function actionSuperSecretCacheFlush(){
        \Yii::$app->cache->flush();
        echo 'dude.. the fu!';
    }
}
