<?php

namespace app\controllers;

use app\models\Language;
use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\HttpException;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionChangeLanguage($lang){
        $l = \app\modules\user\models\Language::findOne($lang);

        if($l !== null){
            Yii::$app->session->set('lang', $lang);
        }
        if(!\Yii::$app->user->isGuest){
            Yii::$app->session->setFlash('info', \Yii::t('app', "Please change your profile settings to permanently change the language!"));
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

    public function actionError()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            return 'Exception not triggered';
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = $this->defaultName ?: Yii::t('yii', 'Error');
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            if(YII_DEBUG){
                echo "Unkown Error: ".$exception->getMessage();
                debug_backtrace(); exit();
            }
            $message = $this->defaultMessage ?: Yii::t('yii', 'An internal server error occurred.');
            if(YII_DEBUG){
                get_call_stack();
                exit();
            }
        }

        \Yii::$app->clog->notice('page.error.'.$code, [
            'code' => $code,
            'name' => $name,
            'message' => $message,
            'url' => \Yii::$app->request->getUrl(),
        ]);

        if (Yii::$app->getRequest()->getIsAjax()) {
            return "$name: $message";
        } else {
            return $this->render('error', [
                'name' => $name,
                'message' => $message,
                'exception' => $exception,
            ]);
        }
    }
}
