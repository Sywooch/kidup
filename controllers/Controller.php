<?php
namespace app\controllers;

use app\modules\search\models\IpLocation;
use app\modules\user\models\Profile;
use Yii;

class Controller extends \yii\web\Controller
{
    public $transparentNav;
    public $noContainer;
    public $noHeaderSpace;
    public $noFooter;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'company' => [
                'class' => 'yii\web\ViewAction',
                'viewPrefix' => '@app/views/static/company'
            ],
            'help' => [
                'class' => 'yii\web\ViewAction',
                'viewPrefix' => '@app/views/static/help',
            ],
            'tutorial' => [
                'class' => 'yii\web\ViewAction',
                'viewPrefix' => '@app/views/static/tutorial'
            ],
        ];
    }

    public function __construct($id, $controller)
    {
        if (YII_ENV == 'test') {
            Yii::setAlias('@web', Yii::getAlias('@web') . '/index-test.php');
        }
        // this disallows websites to frame kidup
        \Yii::$app->view->registerMetaTag(['http-equiv' => 'X-FRAME-OPTIONS', 'content' => 'DENY']);
        $data = [
            'referrer' => \Yii::$app->request->referrer,
            'url' => \Yii::$app->request->getUrl(),
            'method' => \Yii::$app->request->getMethod(),
            'ip' => \Yii::$app->request->getUserIP()
        ];
        Yii::beginProfile('logging');
//        \Yii::$app->clog->info('page.view', $data);
        // todo would be awesome to track this, but it takes about 150 ms, which is waay to long
        Yii::endProfile('logging');

        Yii::$app->setHomeUrl('@web/home');
        if (Yii::$app->session->has('lang')) {
            Yii::$app->language = Yii::$app->session->get('lang');
        } else {
            if (!\Yii::$app->user->isGuest) {
                $p = \Yii::$app->db->cache(function () {
                    return Profile::find()->where(['user_id' => \Yii::$app->user->id])->select('language')->one();
                },60*60);
                if ($p->language !== null) {
                    Yii::$app->language = $p->language;
                } else {
                    Yii::$app->language = 'da-DK';
                }
            } else {
                $location = IpLocation::get(\Yii::$app->request->getUserIP());
                if ($location['country'] == 'Denmark') {
                    Yii::$app->language = 'da-DK';
                } else {
                    Yii::$app->language = 'en-US';
                }
                Yii::$app->session->set('lang', Yii::$app->language);
            }
        }

        // set the locale for Carbon
        \Carbon\Carbon::setLocale(Yii::$app->language[0] . \Yii::$app->language[1]);
        setlocale(LC_TIME, str_replace('-', '_', Yii::$app->language));
        return parent::__construct($id, $controller);
    }

}