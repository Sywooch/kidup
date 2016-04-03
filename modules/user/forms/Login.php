<?php


namespace user\forms;

use user\helpers\Password;
use user\models\user\User;
use yii\base\Model;

/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Login extends Model
{
    /** @var string User's email or username */
    public $login;

    /** @var string User's plain password */
    public $password;

    /** @var string Whether to remember the user */
    public $rememberMe = false;

    /** @var \user\models\user\User */
    protected $user;

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'login' => \Yii::t('user.login.login', 'Login'),
            'password' => \Yii::t('user.login.password', 'Password'),
            'rememberMe' => \Yii::t('user.login.remember_me', 'Remember Me Next Time'),
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            ['login', 'trim'],
            [
                'password',
                function ($attribute) {
                    if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
                        $this->addError($attribute, \Yii::t('user.login.invalid_error', 'Invalid login or password'));
                    }
                }
            ],
            [
                'login',
                function ($attribute) {
                    if ($this->user !== null) {
                        if ($this->user->getIsBlocked()) {
                            $this->addError($attribute, \Yii::t('user.login.error_account_blocked', 'Your account has been blocked'));
                        }
                    }
                }
            ],
            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Validates form and logs the user in.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return \Yii::$app->getUser()->login($this->user, 30*24*60*60);
        } else {
            return false;
        }
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'login-form';
    }

    /** @inheritdoc */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->user = User::findOne(['email' => $this->login]);
            return true;
        } else {
            return false;
        }
    }
}
