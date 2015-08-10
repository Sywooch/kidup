<?php

/*
 * This file is part of the app\modules project.
 *
 * (c) app\modules project <http://github.com/app\models/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\user\controllers;

use app\modules\user\Finder;
use app\modules\user\forms\PostRegistration;
use app\modules\user\forms\PostRegistrationProfile;
use app\modules\user\forms\Registration;
use app\modules\user\models\User;
use yii\base\Model;
use app\controllers\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * RegistrationController is responsible for all registration process, which includes registration of a new account,
 * resending confirmation tokens, email confirmation and registration via social networks.
 *
 * @property \app\modules\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RegistrationController extends Controller
{
    /** @var Finder */
    protected $finder;

    /**
     * @param string           $id
     * @param \yii\base\Module $module
     * @param Finder           $finder
     * @param array            $config
     */
    public function __construct($id, $module, Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($id, $module, $config);
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['register', 'connect'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['confirm', 'post-registration'], 'roles' => ['?', '@']],
                ],
                'denyCallback' => function ($rule, $action) {
                    if($action == 'register'){
                        return $this->goHome();
                    }
                    return $this->redirect('@web/user/'.\Yii::$app->user->id);
                }
            ],
        ];
    }

    /**
     * Displays the registration page.
     * After successful registration if enableConfirmation is enabled shows info message otherwise redirects to home page.
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionRegister()
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException;
        }

        $model = \Yii::createObject(Registration::className());

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
            return $this->redirect('@web/user/registration/post-registration');
        }

        return $this->render('register', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }

    /**
     * Displays page where user can create new account that will be connected to social account.
     * @param  integer $account_id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionConnect($account_id)
    {
        $account = $this->finder->findAccountById($account_id);

        if ($account === null || $account->getIsConnected()) {
            throw new NotFoundHttpException;
        }

        /** @var User $user */
        $user = \Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'connect'
        ]);

        if ($user->load(\Yii::$app->request->post()) && $user->create()) {
            $account->user_id = $user->id;
            $account->save(false);
            \Yii::$app->user->login($user, $this->module->rememberFor);
            return $this->redirect('@web/user/registration/post-registration');
        }

        return $this->render('connect', [
            'model'   => $user,
            'account' => $account
        ]);
    }

    /**
     * Confirms user's account. If confirmation was successful logs the user and shows success message. Otherwise
     * shows error message.
     * @param  integer $id
     * @param  string  $code
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code)
    {
        $user = $this->finder->findUserById($id);

        if ($user === null || $this->module->enableConfirmation == false) {
            throw new NotFoundHttpException;
        }

        $user->attemptConfirmation($code);

        return $this->goHome();
    }


    /**
     * Displays the post-registration fields
     */
    public function actionPostRegistration(){
        $user =  User::find()->where(['id' => \Yii::$app->user->id])->one();

        $model = \Yii::createObject(PostRegistrationProfile::className());

        $this->performAjaxValidation($model);

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->goBack();
        }
        if($user->profile->description == null || $user->profile->first_name == null || $user->profile->last_name == null){
            return $this->render('post_registration',[
                'model' => $model,
            ]);
        }else{
            \Yii::$app->session->addFlash('success', \Yii::t('user', 'Thank you {0} for completing your profile, and welcome to KidUp!', [
                $user->profile->first_name
            ]));
            return $this->redirect('@web/user/settings/profile');
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
