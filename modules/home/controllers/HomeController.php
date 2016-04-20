<?php

namespace home\controllers;

use app\components\web\Controller;
use home\forms\Search;
use item\models\category\Category;
use item\models\item\Item;
use user\models\profile\Profile;
use user\models\user\User;
use Yii;
use yii\helpers\FormatConverter;
use yii\helpers\Html;
use yii\helpers\StringHelper;

class HomeController extends Controller
{
    public function behaviors()
    {
        return [
            // access control, everybody can view this
            [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'fake-home'],
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
                    \Yii::$app->session->getAllFlashes(),
                    \Yii::$app->user->isGuest,
                    \Yii::$app->request->get('ref')
                ],
            ],
        ];
    }

    public function actionFakeHome()
    {
        return $this->render("fake-home");
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
        $items = Item::getRecommended(4);

        $rotationImage = rand(1, 2);
        $fileName = "kidup/home/rotating-headers/header-{$rotationImage}.jpg";

        $res = $this->render('index', [
            'categories' => $categories,
            'items' => $items,
            'searchModel' => $searchModel,
            'image' => $rotationImage,
            'rotatingImage' => $fileName
        ]);

        return $res;
    }

    public function actionChangeLanguage($lang)
    {
        $l = \user\models\language\Language::findOne($lang);

        if ($l !== null) {
            if (!\Yii::$app->user->isGuest) {
                $u = (new \DeepCopy\DeepCopy())->copy(\Yii::$app->user->identity->profile);
                $u->language = $lang;
                $u->save();
            }
            Yii::$app->session->set('lang', $lang);
//            Yii::$app->session->close(); // forces a write to the db, not done by default because it returns a redirect, not a page
        } else {
            Yii::error('Language undefined: ' . $lang);
        }

        if (isset($_SERVER["HTTP_REFERER"])) {
            return "<script>window.location='{$_SERVER['HTTP_REFERER']}';</script>" . \Yii::t("kidup.change_language.redirect_text",
                "Redirecting you to previous page. Troubles loading? {link}click here{linkOut}", [
                    'link' => Html::beginTag("a", ['href' => $_SERVER["HTTP_REFERER"]]),
                    'linkOut' => '</a>'
                ]);
        } else {
            return $this->goHome();
        }
    }

    public function actionSuperSecretCacheFlush()
    {
        \Yii::$app->cache->flush();
        echo 'dude.. the fu!!';
    }

    public function actionTest()
    {
//        \Yii::$app->runAction('v2/items', []);
//        $item = Item::find()->innerJoinWith('wishListItems.user')->andWhere(['user_id' => 1])->all();
        $params = [
            'users',
            1,
            'wish-list-items',
            'items',
            'owners'
        ];
        foreach ($params as $i => &$param) {
            if(is_string($param)){
                $param = ucwords(str_replace("-", ' ', $param));
                $param = lcfirst(str_replace(" ", '', $param));
            }
            if($i == 0){
                $param = \yii\helpers\Inflector::pluralize($param);
            }
            if($i == 2){
                $param = \yii\helpers\Inflector::pluralize($param);
            }
            if($i > 2){
                $param = \yii\helpers\Inflector::singularize($param);
            }
        }
        $innerJoin = implode('.', array_slice($params, 2));
        $url = 'api/v2/'.$params[0];
//        \yii\helpers\VarDumper::dump($url,10,true); exit();
        \Yii::$app->runAction($url, ['id' => $params[1]]);
//        $profile = User::find()->innerJoinWith("wishListItems.item.owner as rel")->andWhere(['user_id' => 1]);
    }
}
