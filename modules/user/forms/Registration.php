<?php


namespace app\modules\user\forms;
use app\modules\user\models\User;
use app\modules\user\Module;
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
            'class'    => User::className(),
            'scenario' => 'register'
        ]);
        $this->module = \Yii::$app->getModule('user');
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
//            [['first_name', 'last_name'], 'filter', 'filter' => 'trim'],
            // [['first_name', 'last_name'], 'match', 'pattern' => '/^[a-zA-Z]\w+$/'],
//            [['first_name', 'last_name'], 'required'],
            // [['first_name', 'last_name'], 'unique', 'targetClass' => $this->module->modelMap['User'],
                //'message' => \Yii::t('user', 'This first_name has already been taken')],
//            [['first_name', 'last_name'], 'string', 'min' => 2, 'max' => 20],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => $this->module->modelMap['User'],
                'message' => \Yii::t('user', 'This email address has already been taken')],

            ['password', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            ['password', 'string', 'min' => 6],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email'    => \Yii::t('user', 'Email'),
            'first_name' => \Yii::t('user', 'First Name'),
            'last_name' => \Yii::t('user', 'Last Name'),
            'password' => \Yii::t('user', 'Password'),
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
            'email'    => $this->email,
            // 'first_name' => $this->first_name,
            // 'last_name' => $this->last_name,
            'password' => $this->password
        ]);

        if($reg = $this->user->register()){
            $u = User::find()->where(['email' => $this->email])->one();
            return \Yii::$app->user->login($u);
        }else{
            return $reg;
        }
    }
}