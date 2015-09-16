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

use app\components\Event;
use app\controllers\Controller;
use app\modules\images\components\ImageManager;
use app\modules\mail\models\Token;
use app\modules\user\Finder;
use app\modules\user\forms\LocationForm;
use app\modules\user\forms\Settings;
use app\models\helpers\SelectData;
use app\modules\user\models\Account;
use app\modules\user\models\Profile;
use app\modules\user\models\User;
use yii\authclient\ClientInterface;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * SettingsController manages updating user settings (e.g. profile, email and password).
 *
 * @property \app\modules\user\Module $module
 */
class SettingsController extends Controller
{
    /** @inheritdoc */
    public $defaultAction = 'profile';

    /** @var Finder */
    protected $finder;

    /**
     * @param string $id
     * @param \yii\base\Module $module
     * @param Finder $finder
     * @param array $config
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'profile',
                            'location',
                            'payout-preference',
                            'verification',
                            'connect',
                            'disconnect',
                            'confirm',
                            'phonecode',
                            'email',
                            'account'
                        ],
                        'roles' => ['@']
                    ],
                ]
            ],
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        return [
            'connect' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'connect'],
            ]
        ];
    }

    /**
     * Shows profile settings form.
     * @return string|\yii\web\Response
     */
    public function actionProfile()
    {
        /** @var \app\modules\User\models\Profile $model * */
        $model = Profile::findOne(['user_id' => \Yii::$app->user->identity->getId()]);

        $this->performAjaxValidation($model);
        if ($model->load(\Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'img');
            if ($image !== null) {
                if (!in_array($image->extension, ['png', 'jpg'])) {
                    \Yii::$app->session->addFlash('warning', \Yii::t('user', "File format not allowed"));
                    $model->save();
                    return $this->refresh();
                }
                $model->setAttribute('img', (new ImageManager())->upload($image));
            } else {
                $model->setAttribute('img', $model->oldAttributes['img']);
            }
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Your profile has been updated'));
                return $this->refresh();
            }
        }

        $page = $this->renderPartial('profile', [
            'model' => $model,
        ]);
        return $this->render('_wrapper', [
            'page' => $page,
            'title' => ucfirst(\Yii::t('title', 'Profile settings')) . ' - ' . \Yii::$app->name
        ]);
    }

    public function actionLocation()
    {
        $model = \Yii::createObject(LocationForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post())) {

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account details have been updated'));
                return $this->refresh();
            }

        }

        $page = $this->renderPartial('location', [
            'model' => $model,
        ]);
        return $this->render('_wrapper', [
            'page' => $page,
            'title' => ucfirst(\Yii::t('title', 'Location Settings')) . ' - ' . \Yii::$app->name
        ]);
    }

    /**
     * Displays page where user can update account settings (username, email or password).
     * @return string|\yii\web\Response
     */
    public function actionAccount()
    {
        /** @var Settings $model */
        $model = new Settings();

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account details have been updated'));
                \Yii::$app->session->remove('lang'); // language might have changed
                return $this->refresh();
            }
        }

        $languages = SelectData::languages();

        $currencies = SelectData::currencies();

        $phoneCountries = SelectData::phoneCountries();

        $page = $this->renderPartial('account', [
            'model' => $model,
            'languages' => $languages,
            'currencies' => $currencies,
            'phoneCountries' => $phoneCountries
        ]);
        return $this->render('_wrapper', [
            'page' => $page,
            'title' => ucfirst(\Yii::t('title', 'Account Settings')) . ' - ' . \Yii::$app->name
        ]);
    }

    public function actionPayoutPreference()
    {
        $model = new \app\modules\user\forms\PayoutPreference();

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account details have been updated'));
                return $this->refresh();
            }
        }

        $page = $this->renderPartial('payout_preference', [
            'model' => $model,
        ]);
        return $this->render('_wrapper', [
            'page' => $page,
            'title' => ucfirst(\Yii::t('title', 'Payout Preference')) . ' - ' . \Yii::$app->name
        ]);
    }

    /**
     * Attempts changing user's password, not used atm
     * @param  integer $id
     * @param  string $code
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code)
    {
        /** @var \app\modules\User\models\User $user * */
//        $user = $this->finder->findUserById($id);
//
//        if ($user === null || $this->module->emailChangeStrategy == Module::STRATEGY_INSECURE) {
//            throw new NotFoundHttpException;
//        }
//
//        $user->attemptEmailChange($code);
//
//        return $this->redirect(['account']);
    }

    /**
     * Displays list of connected network accounts.
     * @return string
     */
    public function actionVerification($confirm_email = false, $confirm_phone = false)
    {
        /** @var \app\modules\User\models\Profile $profile * */
        $profile = Profile::findOne(\Yii::$app->user->id);

        if ($confirm_email && !$profile->email_verified) {
            // resend email verification
            $user = User::findOne(\Yii::$app->user->id);
            Event::trigger($user, User::EVENT_USER_REQUEST_EMAIL_RECONFIRM);
        }

        if ($confirm_phone && !$profile->phone_verified) {
            Token::deleteAll([
                'user_id' => \Yii::$app->user->id,
                'type' => Token::TYPE_PHONE_CODE
            ]);
            $token = \Yii::createObject([
                'class' => Token::className(),
                'user_id' => $this->user->id,
                'type' => Token::TYPE_PHONE_CODE,
            ]);
            $token->save();
            if ($profile->sendPhoneVerification($token)) {
                return $this->redirect('phonecode');
            } else {
                \Yii::$app->session->addFlash('info', \Yii::t('user', 'SMS could not be send, please try again.'));
                return $this->refresh();
            }
        }

        $page = $this->renderPartial('verification', [
            'user' => \Yii::$app->user->identity,
            'profile' => Profile::findOne(['user_id' => \Yii::$app->user->id])
        ]);

        return $this->render('_wrapper', [
            'page' => $page,
            'title' => ucfirst(\Yii::t('title', 'Trust and Verification')) . ' - ' . \Yii::$app->name
        ]);
    }

    public function actionPhonecode($code = null)
    {
        /** @var \app\modules\User\models\Profile $p * */
        $p = Profile::findOne(\Yii::$app->user->id);

        if ($code !== null) {
            /** @var \app\modules\Mail\models\Token $token * */
            $token = Token::findOne([
                'user_id' => \Yii::$app->user->id,
                'code' => $code,
                'type' => Token::TYPE_PHONE_CODE
            ]);
            if ($token == null) {
                \Yii::$app->session->setFlash('error', \Yii::t('app', "Confirmation code not found, please try again"));
            } else {
                $p->setScenario('phonecode');
                $p->phone_verified = 1;
                $p->save();

                $token->delete();
                \Yii::$app->session->setFlash('success',
                    \Yii::t('app', "Thank you, your phone number has been verified!"));

                return $this->redirect(['/user/settings/verification']);
            }
        }

        return $this->render('phone_code', [
            'profile' => $p
        ]);
    }

    /**
     * Disconnects a network account from user.
     * @param  integer $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionDisconnect($id)
    {
        $account = $this->finder->findAccountById($id);
        if ($account === null) {
            throw new NotFoundHttpException;
        }
        if ($account->user_id != \Yii::$app->user->id) {
            throw new ForbiddenHttpException;
        }
        $account->delete();

        return $this->redirect(['verification']);
    }

    /**
     * Connects social account to user.
     * @param  ClientInterface $client
     * @return \yii\web\Response
     */
    public function connect(ClientInterface $client)
    {
        $attributes = $client->getUserAttributes();
        $provider = $client->getId();
        $clientId = $attributes['id'];

        $account = $this->finder->findAccountByProviderAndClientId($provider, $clientId);

        if ($account === null) {
            $account = \Yii::createObject([
                'class' => Account::className(),
                'provider' => $provider,
                'client_id' => $clientId,
                'data' => json_encode($attributes),
                'user_id' => \Yii::$app->user->id,
            ]);
            $account->save(false);
            \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account has been connected'));
        } else {
            \Yii::$app->session->setFlash('error',
                \Yii::t('user', 'This account has already been connected to another user'));
        }

        $this->action->successUrl = Url::to(['/user/settings/verification']);
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
