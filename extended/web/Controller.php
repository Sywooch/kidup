<?php
namespace app\extended\web;

use app\components\UrlHelper;
use user\models\profile\Profile;
use Yii;
use yii\helpers\Url;
use yii\web\Cookie;

class Controller extends \yii\web\Controller
{
    public $transparentNav;
    public $noContainer;
    public $noHeaderSpace;
    public $noFooter;

    public function __construct($id, $controller)
    {
        // potential fix for cloudflare user IP adress
        if (YII_ENV !== 'test' && !YII_CONSOLE) {
            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            }
            if (isset($_SERVER["REMOTE_ADDR"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["REMOTE_ADDR"];
            }
        }

        if (YII_ENV == 'test') {
            Yii::setAlias('@web', Yii::getAlias('@web') . '/index-test.php');
        }

        \Yii::$app->view->registerMetaTag(['http-equiv' => 'X-FRAME-OPTIONS', 'content' => 'DENY']);

        Yii::$app->setHomeUrl('@web/home');
        if (Yii::$app->session->has('lang')) {
            Yii::$app->language = Yii::$app->session->get('lang');
        } else {
            if ((new UrlHelper())->isEnglishDomain()) {
                Yii::$app->language = 'en-US';
            } elseif ((new UrlHelper())->isDanishDomain()) {
                Yii::$app->language = 'da-DK';
            } elseif (YII_ENV !== 'prod') {
                Yii::$app->language = 'en-US';
            } else {
                Yii::$app->language = 'da-DK';
            }
            Yii::$app->session->set('lang', Yii::$app->language);
        }

        if (\Yii::$app->request->get("ref") !== null) {
            $cookie = new Cookie([
                'name' => 'kidup_referral',
                'value' => \Yii::$app->request->get("ref"),
                'expire' => time() + 30 * 24 * 60 * 60,
            ]);
            \Yii::$app->getResponse()->getCookies()->add($cookie);
            \Yii::$app->session->set('after_login_url', Url::to('@web/user/referral/index', true));
        }

        // set the locale for CarbonP
        \Carbon\Carbon::setLocale(Yii::$app->language[0] . \Yii::$app->language[1]);
        setlocale(LC_TIME, str_replace('-', '_', Yii::$app->language));
        return \yii\web\Controller::__construct($id, $controller);
    }
}