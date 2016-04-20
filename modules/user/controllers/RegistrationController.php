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
use app\components\Event;
use user\forms\PostRegistrationProfile;
use user\forms\Registration;
use user\models\socialAccount\SocialAccount;
use user\models\user\User;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * RegistrationController is responsible for all registration process, which includes registration of a new account,
 * resending confirmation tokens, email confirmation and registration via social networks.
 *
 * @property \user\Module $module
 */
class RegistrationController extends Controller
{
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
                    if ($action == 'register') {
                        return $this->goHome();
                    }
                    return $this->redirect('@web/user/' . \Yii::$app->user->id);
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
        $model = new Registration();

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
            return $this->redirect(User::afterLoginUrl('registration'));
        }

        return $this->render('register', [
            'model' => $model,
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
        $account = SocialAccount::findOneOr404($account_id);

        if ($account->getIsConnected()) {
            if ($account->user !== null) {
                return $this->redirect(User::afterLoginUrl('connect'));
            }
        }

        /** @var User $user */
        $user = \Yii::createObject([
            'class' => User::className(),
            'scenario' => 'connect'
        ]);

        $data = \yii\helpers\Json::decode($account->data);
        if (isset($data['email'])) {
            // see if user already exists
            $u = User::findOne(['email' => $data['email']]);
            if ($u !== null) {
                $account->user_id = $u->id;
                $account->save(false);
                \Yii::$app->user->login($u, 30*24*60*60);
                return $this->redirect(User::afterLoginUrl('connect'));
            } else {
                $user->email = $data['email'];
                $user->create();
                $account->user_id = $user->id;
                $account->save(false);
                /**
                 * @var SocialAccount $socialAccount
                 */
                $socialAccount = SocialAccount::findOne($account_id);
                if ($socialAccount !== null) {
                    $socialAccount->fillUserDetails($user);
                    Event::trigger($user, User::EVENT_USER_REGISTER_DONE);
                }
                \Yii::$app->user->login($user, 30*24*60*60);
                return $this->redirect(User::afterLoginUrl('connect_new'));
            }
        }

        if ($user->load(\Yii::$app->request->post()) && $user->create()) {
            $account->user_id = $user->id;
            $account->save(false);
            \Yii::$app->user->login($user, 30*24*60*60);
            return $this->redirect(User::afterLoginUrl('connect_new'));
        }

        return $this->render('connect', [
            'model' => $user,
            'account' => $account
        ]);
    }

    /**
     * Confirms user's account. If confirmation was successful logs the user and shows success message. Otherwise
     * shows error message.
     * @param  integer $id
     * @param  string $code
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code)
    {
        $user = User::findOneOr404($id);

        $user->attemptConfirmation($code);

        return $this->goHome();
    }


    /**
     * Displays the post-registration fields
     */
    public function actionPostRegistration()
    {
        $model = new PostRegistrationProfile();

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post())) {
            // try saving, doesnt matter if it works
            $model->save();
            return $this->redirect(User::afterLoginUrl('post_registration'));
        }

        return $this->render('post_registration', [
            'model' => $model,
        ]);
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
