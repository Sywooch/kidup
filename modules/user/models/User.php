<?php

namespace user\models;

use api\models\Booking;
use api\models\oauth\OauthAccessToken;
use api\models\PayoutMethod;
use app\helpers\Event;
use images\components\ImageHelper;
use item\models\Location;
use notifications\models\Token;
use user\Finder;
use user\helpers\Password;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;
use yii\helpers\Url;
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
 * @property Account[] $accounts
 * @property \user\models\Profile $profile
 *
 * @property \user\models\User|\yii\web\IdentityInterface|null $identity The identity object associated with the currently logged-in user. null is returned if the user is not logged in (not authenticated).
 */
class User extends base\User implements IdentityInterface
{
    // events
    const EVENT_USER_CREATE_INIT = 'user_create_init';
    const EVENT_USER_CREATE_DONE = 'user_create_done';
    const EVENT_USER_REGISTER_INIT = 'user_register_init';
    const EVENT_USER_REGISTER_DONE = 'user_register_done';
    const EVENT_USER_REQUEST_EMAIL_RECONFIRM = 'user_request_email_reconfirm';
    const EVENT_USER_REQUEST_RECOVERY = 'user_request_recovery';

    // following constants are used on secured email changing process
    const OLD_EMAIL_CONFIRMED = 0b1;
    const NEW_EMAIL_CONFIRMED = 0b10;

    const STATUS_SPLASH = -1;

    const ROLE_USER = 0;
    const ROLE_ADMIN = 9;

    /** @var string Plain password. Used for model validation. */
    public $password;

    /** @var \user\Module */
    protected $module;

    /** @var \user\Finder */
    protected $finder;

    private $first_name;
    private $last_name;

    /** @inheritdoc */
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
            throw new HttpException(401, "Access Token expired");
        }
        return $token->user;
    }

    public function scenarios()
    {
        return [
            'default' => ['email', 'password'],
            'register' => ['email', 'password'],
            'connect' => ['email'],
            'create' => ['email', 'password'],
            'update' => ['email', 'password'],
            'settings' => ['email', 'password'],
            'splash' => ['email']
        ];
    }


    /**
     * @return bool Whether the user is confirmed or not.
     */
    public function getIsConfirmed()
    {
        return $this->confirmed_at != null;
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
    public function getIsAdmin()
    {
        return $this->role == 9;
    }

    /**
     * @return Account[] Connected accounts ($provider => $account)
     */
    public function getAccounts()
    {
        $this->init();
        $connected = [];
        $accounts = $this->hasMany($this->module->modelMap['Account'], ['user_id' => 'id'])->all();

        /** @var Account $account */
        foreach ($accounts as $account) {
            $connected[$account->provider] = $account;
        }

        return $connected;
    }

    /** @inheritdoc */
    public function init()
    {
        $this->finder = \Yii::$container->get(Finder::className());
        $this->module = \Yii::$app->getModule('user');

        parent::init();
    }

    /** @inheritdoc */
    public function getId()
    {
        return $this->getAttribute('id');
    }


    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email' => \Yii::t('user.attributes.email', 'Email'),
            'registration_ip' => \Yii::t('user.attributes.registration_ip', 'Registration ip'),
            'unconfirmed_email' => \Yii::t('user.attributes.new_email', 'New email'),
            'password' => \Yii::t('user.attributes.password', 'Password'),
            'created_at' => \Yii::t('user.attributes.registration_time', 'Registration time'),
            'confirmed_at' => \Yii::t('user.attributes.confirmation_time', 'Confirmation time'),
        ];
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            // email rules
            ['email', 'required', 'on' => ['register', 'connect', 'create', 'update']],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique'],
            ['email', 'trim'],
            // password rules
            ['password', 'required', 'on' => ['register']],
            ['password', 'string', 'min' => 6, 'on' => ['register', 'create']],
            ['country', 'required', 'on' => ['register', 'connect']],
            ['registerFirstName', 'required', 'on' => ['register', 'connect']],
            ['registerLastName', 'required', 'on' => ['register', 'connect']],
        ];
    }

    /** @inheritdoc */
    public function validateAuthKey($authKey)
    {
        return User::find()->where(['auth_key' => $authKey])->count() > 0;
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

        if ($this->password == null) {
            $this->password = Password::generate(8);
        }

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
     * If Module::enableGeneratingPassword is set true, this method will generate new 8-char password. After saving user
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
        if (!isset($this->password)) {
            $this->password = Password::generate(8);
        }

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
        if ($payoutMethod == null) {
            return false;
        }

        return true;
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
        if($payoutMethod == 0){
            return false;
        }

        return true;
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
        return Url::to('@web/home');
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

            \Yii::$app->user->login($this, $this->module->rememberFor);

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

        if (!empty($this->password)) {
            $this->setAttribute('password_hash', Password::hash($this->password));
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


    public function isAdmin()
    {
        return ($this->role === self::ROLE_ADMIN);
    }

    /**
     * @return string
     */
    protected function getFlashMessage()
    {
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayoutMethod()
    {
        return $this->hasOne(PayoutMethod::className(), ['user_id' => 'id']);
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


}
