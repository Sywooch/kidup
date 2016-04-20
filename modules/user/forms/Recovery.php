<?php


namespace user\forms;

use app\components\Event;
use user\models\token\Token;
use user\models\user\User;
use yii\base\Model;

/**
 * Model for collecting data on password recovery.
 *
 * @property \user\Module $module
 */
class Recovery extends Model
{
    /** @var string */
    public $email;

    /** @var string */
    public $password;

    /** @var User */
    protected $user;

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email' => \Yii::t('user.recovery.email', 'Email'),
            'password' => \Yii::t('user.recovery.password', 'Password'),
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'request' => ['email'],
            'reset' => ['password']
        ];
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
                'exist',
                'targetClass' => User::className(),
                'message' => \Yii::t('user.recovery.error_not_found', 'There is no user with this email address')
            ],
            [
                'email',
                function ($attribute) {
                    $this->user = User::findOne(['email' => $this->email]);
                }
            ],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Sends recovery message.
     *
     * @return bool
     */
    public function sendRecoveryMessage()
    {
        if ($this->validate()) {
            $u = User::findOne($this->user->id);
            Event::trigger($u, User::EVENT_USER_REQUEST_RECOVERY);
            \Yii::$app->session->setFlash('info',
                \Yii::t('user.recovery.email_has_been_sent_flash',
                    'An email has been sent with instructions for resetting your password'));
            return true;
        }

        return false;
    }

    /**
     * Resets user's password.
     *
     * @param  Token $token
     * @return bool
     */
    public function resetPassword(Token $token)
    {
        if (!$this->validate() || $token->user === null) {
            return false;
        }

        if ($token->user->resetPassword($this->password)) {
            \Yii::$app->session->setFlash('success', \Yii::t('user.recovery.password_changed_successfully',
                'Your password has been changed successfully.'));
            $token->delete();
        } else {
            \Yii::$app->session->setFlash('danger',
                \Yii::t('user.recovery.password_not_changed_error',
                    'An error occurred and your password has not been changed. Please try again later.'));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'recovery-form';
    }
}
