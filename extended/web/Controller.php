<?php
namespace app\extended\web;

use search\models\IpLocation;
use user\models\Profile;
use Yii;

class Controller extends \yii\web\Controller
{
    public $transparentNav;
    public $noContainer;
    public $noHeaderSpace;
    public $noFooter;


    public function __construct($id, $controller)
    {
        \yii\web\Controller::__construct($id, $controller); // do this at first, otherwise https residrect will not work
        // load over https in production, .htaccess didnt work for some reason
        if(YII_ENV == 'prod'){
            $r = \Yii::$app->request;
            if($r->isGet && strpos($r->absoluteUrl, 'https') !== 0){
                return $this->redirect(str_replace('http://', 'https://', $r->absoluteUrl));
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
            if (!\Yii::$app->user->isGuest) {
                $p = Profile::find()->where(['user_id' => \Yii::$app->user->id])->select('language')->one();
                if ($p->language !== null) {
                    Yii::$app->language = $p->language;
                } else {
                    Yii::$app->language = 'da-DK';
                }
            } else {
                $location = IpLocation::get(\Yii::$app->request->getUserIP());
                if ($location->country == 'Netherlands' || $location->country == 'United States' ||
                    (YII_ENV == 'dev' || YII_ENV == 'test')) {
                    Yii::$app->language = 'en-US';
                } else {
                    Yii::$app->language = 'da-DK';
                }
                Yii::$app->session->set('lang', Yii::$app->language);
            }
        }
        // set the locale for Carbon
        \Carbon\Carbon::setLocale(Yii::$app->language[0] . \Yii::$app->language[1]);
        setlocale(LC_TIME, str_replace('-', '_', Yii::$app->language));
        return true;
    }

}