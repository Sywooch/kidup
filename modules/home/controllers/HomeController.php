<?php

namespace home\controllers;

use app\extended\web\Controller;
use home\forms\Search;
use item\models\Category;
use item\models\Item;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

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

    public function actionFakeHome(){
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
        $l = \user\models\Language::findOne($lang);

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
        echo 'dude.. the fu!';
    }
}
