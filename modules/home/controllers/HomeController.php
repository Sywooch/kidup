<?php

namespace app\modules\home\controllers;

use app\components\Cache;
use app\extended\web\Controller;
use app\modules\home\forms\Search;
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
//            [
//                'class' => 'yii\filters\HttpCache',
//                'only' => ['index'],
//                'cacheControlHeader' => 'public, max-age=300',
//                'enabled' => YII_CACHE,
//                'etagSeed' => function ($action, $params) {
//                    return Json::encode([
//                        Yii::$app->language,
//                        \Yii::$app->session->getAllFlashes(),
//                        \Yii::$app->user->isGuest
//                    ]);
//                },
//            ],
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
            return Category::find()->all();
        }, 24 * 3600);
        $items = Yii::$app->db->cache(function () {
            return Item::find()->limit(4)->orderBy('RAND()')->where(['is_available' => 1])->all();
        }, 6 * 3600);

        $res = $this->render('index', [
            'categories' => $categories,
            'items' => $items,
            'searchModel' => $searchModel
        ]);

        return $res;
    }

    public function actionChangeLanguage($lang){
        $l = \app\modules\user\models\Language::findOne($lang);

        if($l !== null){
            Yii::$app->session->remove('lang');
            Yii::$app->session->set('lang', $lang);
        }else{
            Yii::error('Language undefined: '.$lang);
        }
        if(!\Yii::$app->user->isGuest){
            Yii::$app->session->setFlash('info', \Yii::t('home.flash.use_settings_for_permanent_change',
                "Please change your profile settings to permanently change the language!"));
        }
        if(isset($_SERVER["HTTP_REFERER"])){
            return $this->redirect($_SERVER["HTTP_REFERER"]);
        }else{
            return $this->goHome();
        }
    }

    public function actionSuperSecretCacheFlush(){
        \Yii::$app->cache->flush();
//        \app\components\Cache::remove('item_controller-view');
        echo 'dude.. the fu!';
    }
}
