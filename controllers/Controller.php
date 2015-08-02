<?php
namespace app\controllers;

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
        Yii::setAlias('@assets', Yii::getAlias('@web').'/assets_web');
        if (YII_ENV == 'test') {
            Yii::setAlias('@web', Yii::getAlias('@web').'/index-test.php');
        }
        $data = [
            'referrer' => \Yii::$app->request->referrer,
            'url' => \Yii::$app->request->getUrl(),
            'method' => \Yii::$app->request->getMethod(),
            'ip' => \Yii::$app->request->getUserIP()
        ];
        \Yii::$app->clog->info('page.view', $data);

//        if (YII_ENV == 'test' || YII_ENV == 'dev') {
//            Yii::$app->language = 'en-US';
//            return parent::__construct($id, $controller);
//        }
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
                Yii::$app->language = 'da-DK';
            }
        }

        // set the locale for Carbon
        \Carbon\Carbon::setLocale(Yii::$app->language[0].\Yii::$app->language[1]);
        setlocale(LC_TIME, str_replace('-', '_', Yii::$app->language));
        return parent::__construct($id, $controller);
    }

}