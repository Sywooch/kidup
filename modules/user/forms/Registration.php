<?php


namespace user\forms;

use user\models\User;
use user\Module;
use yii\base\Model;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 */
class Registration extends Model
{
    /** @var string */
    public $email;

    /** @var string */
    public $password;

    /** @var User */
    protected $user;

    /** @var Module */
    protected $module;

    /** @inheritdoc */
    public function init()
    {
        $this->user = \Yii::createObject([
            'class' => User::className(),
            'scenario' => 'register'
        ]);
        $this->module = \Yii::$app->getModule('user');
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => $this->module->modelMap['User'],
                'message' => \Yii::t('user.registration.email_already_in_use', 'This email address has already been taken')
            ],
            ['password', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            ['password', 'string', 'min' => 6],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email' => \Yii::t('user.attributes.email', 'Email'),
            'first_name' => \Yii::t('user.attributes.first_name', 'First Name'),
            'last_name' => \Yii::t('user.attributes.last_name', 'Last Name'),
            'password' => \Yii::t('user.attributes.password', 'Password'),
        ];
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'register-form';
    }

    /**
     * Registers a new user account.
     * @return bool
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->user->setAttributes([
            'email' => $this->email,
            // 'first_name' => $this->first_name,
            // 'last_name' => $this->last_name,
            'password' => $this->password
        ]);

        if ($reg = $this->user->register()) {
            $u = User::find()->where(['email' => $this->email])->one();
            return \Yii::$app->user->login($u, $this->module->rememberFor);
        } else {
            return $reg;
        }
    }
}