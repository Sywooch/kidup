<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com/app\models/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace user\controllers;

use app\components\web\Controller;
use user\forms\Login;
use user\models\socialAccount\SocialAccount;
use user\models\user\User;
use yii\authclient\ClientInterface;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Controller that manages user authentication process.
 *
 * @property \user\Module $module
 */
class SecurityController extends Controller
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['auth', 'login'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['logout', 'login'], 'roles' => ['@']],
                ]
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post']
//                ]
//            ]
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'authenticate'],
            ]
        ];
    }

    /**
     * Displays the login page.
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if(!\Yii::$app->user->isGuest){
            return $this->redirect(User::afterLoginUrl('login'));
        }

        $model = new Login();

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->redirect(User::afterLoginUrl('login'));
        }

        return $this->render('login', [
            'model' => $model,
            'module' => $this->module,
        ]);
    }

    /**
     * Logs the user out and then redirects to the homepage.
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        \Yii::$app->getUser()->logout();
        return $this->goHome();
    }

    /**
     * Logs the user in if this social account has been already used. Otherwise shows registration form.
     * @param  ClientInterface $client
     * @return \yii\web\Response
     */
    public function authenticate(ClientInterface $client)
    {
        $attributes = $client->getUserAttributes();
        $provider = $client->getId();
        $clientId = $attributes['id'];
        
        $account = SocialAccount::findOne([
            'provider' => $provider,
            'client_id' => $clientId
        ]);

        if ($account === null) {
            $account = \Yii::createObject([
                'class' => SocialAccount::className(),
                'provider' => $provider,
                'client_id' => $clientId,
                'data' => json_encode($attributes),
            ]);
            $account->save(false);
        }

        if (null === ($user = $account->user)) {
            $this->action->successUrl = Url::to(['/user/registration/connect', 'account_id' => $account->id]);
        } else {
            \Yii::$app->user->login($user, 30*24*60*60);
            $this->action->successUrl = User::afterLoginUrl('social');
        }
    }

    /**
     * Performs ajax validation.
     * @param Model $model
     * @throws \yii\base\ExitException
     */
    protected function performAjaxValidation(Model $model)
    {
        if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            echo json_encode(ActiveForm::validate($model));
            \Yii::$app->end();
        }
    }
}
