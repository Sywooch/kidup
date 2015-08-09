<?php

namespace app\modules\user\models;

use app\components\Event;
use app\modules\item\models\Location;
use app\modules\mail\models\Token;
use app\modules\message\models\Conversation;
use app\modules\user\Finder;
use app\modules\user\helpers\Password;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 *
 * Database fields:
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $unconfirmed_email
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $registration_ip
 * @property integer $confirmed_at
 * @property integer $blocked_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $flags
 *
 * Defined relations:
 * @property Account[] $accounts
 * @property \app\modules\user\models\Profile $profile
 *
 * @property \app\modules\user\models\User|\yii\web\IdentityInterface|null $identity The identity object associated with the currently logged-in user. null is returned if the user is not logged in (not authenticated).
 */
class User extends \app\models\base\User implements IdentityInterface
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

    /** @var \app\modules\user\Module */
    protected $module;

    /** @var \app\modules\user\Finder */
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
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function scenarios()
    {
        return [
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
        return false;
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
    public function getAuthKey()
    {
        return $this->getAttribute('auth_key');
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email' => \Yii::t('user', 'Email'),
            'registration_ip' => \Yii::t('user', 'Registration ip'),
            'unconfirmed_email' => \Yii::t('user', 'New email'),
            'password' => \Yii::t('user', 'Password'),
            'created_at' => \Yii::t('user', 'Registration time'),
            'confirmed_at' => \Yii::t('user', 'Confirmation time'),
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
        return $this->getAttribute('auth_key') == $authKey;
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
            Event::trigger($this, self::EVENT_USER_REGISTER_DONE);

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
        /**
         * @var $location \app\modules\item\models\Location
         */
//        $location = Location::findOne($this->locations[0]->id);
//        && $location->isValid()
        if (strlen($this->profile->first_name) > 0 && strlen($this->profile->last_name) > 0 ) {
            return true;
        }

        return false;
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
                \Yii::t('user', 'The confirmation link is invalid or expired. Please try requesting a new one.'));
        } else {
            $token->delete();

            $this->confirmed_at = time();

            \Yii::$app->user->login($this);

            if ($this->save(false)) {
                $p = Profile::findOne(['user_id' => \Yii::$app->user->id]);
                $p->email_verified = 1;
                if ($p->save()) {
                    \Yii::$app->session->setFlash('success', \Yii::t('user', 'Thank you, your email is now verified!'));
                } else {
                    \Yii::$app->clog->debug('profile not saved', $p->getErrors());
                }
            } else {
                \Yii::$app->session->setFlash('danger',
                    \Yii::t('user', 'Something went wrong and your account has not been confirmed.'));
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

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('auth_key', \Yii::$app->security->generateRandomString());
            if (\Yii::$app instanceof \yii\web\Application) {
                $this->setAttribute('registration_ip', \Yii::$app->request->userIP);
            }
        }

        if (!empty($this->password)) {
            $this->setAttribute('password_hash', Password::hash($this->password));
        }

        return parent::beforeSave($insert);
    }

    /** @inheritdoc */
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
                $profile = \Yii::createObject([
                    'class' => Profile::className(),
                    'user_id' => $this->id,
                    'currency_id' => 1,
                    'language' => $lang
                ]);
                $profile->setAttribute('img', 'kidup/user/default-face.jpg');
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

                // create conversation with kidup admin
                $c = \Yii::createObject([
                    'class' => Conversation::className(),
                    'initiater_user_id' => 1,
                    'target_user_id' => $this->id,
                    'title' => 'Welcome to kidup!',
                    'created_at' => time()
                ]);
                $c->save();
                $c->addMessage(\Yii::t('user',
                    'Hi there, and welcome to kidup! We hope you have a great time, if you\'ve got any questions, please contact us at info@kidup.dk'),
                    $this->id, 1);
            }
        }

        parent::afterSave($insert, $changedAttributes);
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
}
