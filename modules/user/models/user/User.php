<?php

namespace user\models\user;

use review\models\Review;
use user\models\oauth\OauthAccessToken;
use app\helpers\Event;
use images\components\ImageHelper;
use item\models\location\Location;
use notification\models\base\MobileDevices;
use user\helpers\Password;
use user\models\payoutMethod\PayoutMethod;
use user\models\profile\Profile;
use user\models\setting\Setting;
use user\models\socialAccount\SocialAccount;
use user\models\token\Token;
use user\models\userReferredUser\UserReferredUser;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\rbac\Role;
use yii\web\HttpException;
use yii\web\IdentityInterface;

/**
 *
 * Database fields:
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $unconfirmed_email
 * @property string $password_hash
 * @property integer $registration_ip
 * @property integer $confirmed_at
 * @property integer $blocked_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $auth_key
 * @property integer $flags
 *
 * Defined relations:
 * @property SocialAccount[] $accounts
 * @property \user\models\profile\Profile $profile
 *
 * @property \user\models\user\User|\yii\web\IdentityInterface|null $identity The identity object associated with the currently logged-in user. null is returned if the user is not logged in (not authenticated).
 */
class User extends UserBase implements IdentityInterface
{
    // events
    const EVENT_USER_CREATE_INIT = 'user_create_init';
    const EVENT_USER_CREATE_DONE = 'user_create_done';
    const EVENT_USER_REGISTER_INIT = 'user_register_init';
    const EVENT_USER_REGISTER_DONE = 'user_register_done';
    const EVENT_USER_REQUEST_EMAIL_RECONFIRM = 'user_request_email_reconfirm';
    const EVENT_USER_REQUEST_RECOVERY = 'user_request_recovery';

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DELETED = 3;

    /** @var string Plain password. Used for model validation. */
    public $password;

    private $first_name;
    private $last_name;

    public function fields()
    {
        $fields = [
            'id',
            'description' => function ($model) {
                return $model->profile->description;
            },
            'first_name' => function ($model) {
                return $model->profile->first_name;
            },
            'last_name' => function ($model) {
                return $model->profile->last_name;
            },
            'email_verified' => function ($model) {
                return (bool)$model->profile->email_verified;
            },
            'phone_verified' => function ($model) {
                return (bool)$model->profile->phone_verified;
            },
            'img' => function ($model) {
                return ImageHelper::urlSet($model->profile->getAttribute('img'), true);
            },
            'image' => function($model){
                return ImageHelper::url($model->profile->getAttribute('img'));
            },
            'email' => function ($model) {
                return $model->email;
            },
            'phone_number' => function () {
                return $this->profile->getPhoneNumber();
            },
            'language' => function () {
                return $this->profile->language;
            },
            'review_score' => function () {
                return (new Review())->computeOverallUserScore($this);
            },
            'created_at' => function () {
                return $this->created_at;
            },
            'can_accept_booking' => function () {
                return $this->canAcceptBooking();
            }
        ];
        if (strpos(\Yii::$app->request->getQueryParam('expand'), 'items') !== false) {
            $fields['items'] = function ($model) {
                return Item::find()->where([
                    'owner_id' => $model->id,
                    'is_available' => 1,
                ])->orderBy('created_at DESC')->all();
            };
        }

        if (\Yii::$app->user->isGuest || ($this->id !== \Yii::$app->user->id && !$this->allowPrivateAttributes(\Yii::$app->user->identity))) {
            foreach (['email', 'phone_number', 'language'] as $item) {
                unset($fields[$item]);
            }
        }

        if ($this->id !== \Yii::$app->user->id) {
            foreach (['can_accept_booking'] as $item) {
                unset($fields[$item]);
            }
        }
        return $fields;
    }


    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Defines whether this user is the owner
     * @return bool
     */
    public function isOwner(){
        return \Yii::$app->user->id === $this->id;
    }

    public static function tableName()
    {
        return '{{%user}}';
    }

    /** @inheritdoc */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /** @inheritdoc */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $token = OauthAccessToken::find()->where(['access_token' => $token])->one();
        if ($token == null) {
            return false;
        }
        if ($token->expires < time()) {
            throw new HttpException(401,"Access Token expired");
        }
        return $token->user;
    }

    /**
     * @return bool Whether the user is blocked or not.
     */
    public function getIsBlocked()
    {
        return $this->blocked_at != null;
    }

    /**
     * @return bool Whether the user is an admin or not.
     */
    public function isAdmin()
    {
        return $this->role == self::ROLE_ADMIN;
    }

  
    /** @inheritdoc */
    public function validateAuthKey($authKey)
    {
        return User::find()->where(['auth_key' => $authKey])->count() > 0;
    }

    /** @inheritdoc */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * This method is used to create new user account. If password is not set, this method will generate new 8-char
     * password. After saving user to database, this method uses mailer component to send credentials
     * (username and password) to user via email.
     *
     * @return bool
     */
    public function create()
    {
//        $this->init();
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $this->confirmed_at = time();

        $this->trigger(self::EVENT_USER_CREATE_INIT);
        if ($this->save()) {
            $this->trigger(self::EVENT_USER_CREATE_DONE);
            return true;
        }

        return false;
    }

    /**
     * This method is used to register new user account. If Module::enableConfirmation is set true, this method
     * will generate new confirmation token and use mailer to send it to the user. Otherwise it will log the user in.
     * After saving user
     * to database, this method uses mailer component to send credentials (username and password) to user via email.
     *
     * @return bool
     */
    public function register()
    {
        $this->init();
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }
        $this->confirmed_at = time();

        Event::trigger($this, self::EVENT_USER_REGISTER_INIT);
        if ($this->save()) {
            try{
                Event::trigger($this, self::EVENT_USER_REGISTER_DONE);
            }catch(\yii\base\UnknownMethodException $e){
                return true;
            }

            return true;
        }

        return false;
    }

    public function hasValidPayoutMethod()
    {
        $payoutMethod = PayoutMethod::findOne(['user_id' => $this->id]);
        return $payoutMethod != null;
    }

    public function canMakeBooking()
    {
        return true;
    }

    public function canAcceptBooking()
    {
        if (strlen($this->profile->first_name) == 0 || strlen($this->profile->last_name) == 0) {
            return false;
        }

        $payoutMethod = PayoutMethod::find()->where(['user_id' => \Yii::$app->user->id])->count();
        return $payoutMethod != null;
    }

    /**
     * Returns the place a user should go after login/registering/sociallogin
     * @var string $type (login,connect,connect_new,registration,post_registration)
     * @return string
     */
    public static function afterLoginUrl($type)
    {

        // always follow the after_login_url if set
        if (\Yii::$app->session->has('after_login_url')) {
            $loginUrl = \Yii::$app->session->get('after_login_url');
            \Yii::$app->session->remove('after_login_url');
            return $loginUrl;
        }

        if ($type == 'login' || $type == 'connect' || $type == 'post_registration') {
            if (strpos(Url::previous(), 'user/login') !== false) {
                return Url::to('@web/home');
            }
            return Url::previous();
        }
        if ($type == 'registration' || $type == 'connect_new') {
            return Url::to('@web/user/registration/post-registration');
        }
        return Url::previous();
    }

    /**
     * This method attempts user confirmation. It uses finder to find token with given code and if it is expired
     * or does not exist, this method will throw exception.
     *
     * If confirmation passes it will return true, otherwise it will return false.
     *
     * @param  string $code Confirmation code.
     */
    public function attemptConfirmation($code)
    {
        $this->init();
        $token = Token::find()->where([
            'user_id' => $this->getAttribute('id'),
            'code' => $code,
            'type' => Token::TYPE_CONFIRMATION,
        ])->one();
        if ($token === null || $token->isExpired) {
            \Yii::$app->session->setFlash('danger',
                \Yii::t('user.confirmation.link_invalid_or_expired',
                    'The confirmation link is invalid or expired. Please try requesting a new one.'));
        } else {
            $token->delete();

            $this->confirmed_at = time();

            \Yii::$app->user->login($this, 30*24*60*60);

            if ($this->save(false)) {
                $p = Profile::findOne(['user_id' => \Yii::$app->user->id]);
                $p->email_verified = 1;
                if ($p->save()) {
                    \Yii::$app->session->setFlash('success', \Yii::t('user.confirmation.email_verified_success',
                        'Thank you, your email is now verified!'));
                } else {
                    \Yii::error("Profile not saved" . Json::encode($p->getErrors()));
                }
            } else {
                \Yii::$app->session->setFlash('danger',
                    \Yii::t('user.confirmation.some_unknown_error',
                        'Something went wrong and your account has not been confirmed.'));
            }
        }
    }

    /**
     * Resets password.
     *
     * @param  string $password
     * @return bool
     */
    public function resetPassword($password)
    {
        return (bool)$this->updateAttributes(['password_hash' => Password::hash($password)]);
    }

    /**
     * Confirms the user by setting 'blocked_at' field to current time.
     */
    public function confirm()
    {
        return (bool)$this->updateAttributes(['confirmed_at' => time()]);
    }

    /**
     * Blocks the user by setting 'blocked_at' field to current time.
     */
    public function block()
    {
        return (bool)$this->updateAttributes(['blocked_at' => time()]);
    }

    /**
     * Blocks the user by setting 'blocked_at' field to null.
     */
    public function unblock()
    {
        return (bool)$this->updateAttributes(['blocked_at' => null]);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            if (\Yii::$app instanceof \yii\web\Application) {
                $this->setAttribute('registration_ip', \Yii::$app->request->userIP);
            }
            $this->setAttribute('auth_key', \Yii::$app->security->generateRandomString());
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            if (!\Yii::$app->has('session')) {
                // this is probably console environment
                $lang = 'da-DK';
            } else {
                $lang = \Yii::$app->session->has('lang') ? \Yii::$app->session->get('lang') : 'da-DK';
            }

            if ($this->id !== 1) {
                $this->setNewUserDefaults($lang);
            }


            if (!\Yii::$app->request->isConsoleRequest) {
                $cookie = \Yii::$app->getRequest()->getCookies()->getValue('kidup_referral');
                if ($cookie) {
                    (new UserReferredUser())->userIsReferredByUser($this, $cookie);
                    \Yii::$app->getResponse()->getCookies()->remove(\Yii::$app->getRequest()->getCookies()->get('kidup_referral'));
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Create somedefaults for a new user
     * @param $lang
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    private function setNewUserDefaults($lang)
    {
        // todo check that unique?
        $this->referral_code = \Yii::$app->security->generateRandomString(8);
        $this->save();
        $profile = \Yii::createObject([
            'class' => Profile::className(),
            'user_id' => $this->id,
            'currency_id' => 1,
            'language' => $lang
        ]);
        $profile->setAttribute('img', ImageHelper::DEFAULT_USER_FACE);
        $profile->save(false);
        $location = \Yii::createObject([
            'class' => Location::className(),
            'user_id' => $this->id,
            'type' => Location::TYPE_MAIN,
            'country' => 1
        ]);
        $location->save(false);

        $settings = [
            Setting::BOOKING_STATUS_CHANGE,
            Setting::MAIL_BOOKING_REMINDER,
            Setting::NEWSLETTER,
            Setting::MESSAGE_UPDATE,
        ];
        foreach ($settings as $setting) {
            $s = \Yii::createObject([
                'class' => Setting::className(),
                'type' => $setting,
                'value' => 1,
                'user_id' => $this->id
            ]);
            $s->save();
        }
        return true;
    }

    /**
     * @return string
     */
    protected function getFlashMessage()
    {
        return false;
    }

    public function getAuthKey()
    { 
        return $this->auth_key;
    }

    /**
     * Whether a given user is allowed access to private attributes of this user
     * @param User $user
     * @return bool
     */
    public function allowPrivateAttributes(User $user)
    {
        $c = Booking::find()
            ->orWhere(['item.owner_id' => $user->id])
            ->orWhere(['renter_id' => $user->id])
            ->innerJoinWith('item')
            ->count();
        return $c > 0;
    }

    public function getUserAcceptsPushNotifications(){
        $devices = MobileDevices::find()->where([
            'user_id' =>$this->id,
            'is_subscribed' => 1
        ])->count();
        return $devices > 0;
    }
}
