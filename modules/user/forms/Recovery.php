<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com//>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace user\forms;

use app\helpers\Event;
use notifications\models\Mailer;
use notifications\models\Token;
use user\Finder;
use user\models\User;
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

    /** @var \user\Module */
    protected $module;

    /** @var Finder */
    protected $finder;

    /**
     * @param Mailer $mailer
     * @param Finder $finder
     * @param array $config
     */
    public function __construct(Finder $finder, $config = [])
    {
        $this->module = \Yii::$app->getModule('user');
        $this->finder = $finder;
        parent::__construct($config);
    }

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
                'targetClass' => $this->module->modelMap['User'],
                'message' => \Yii::t('user.recovery.error_not_found', 'There is no user with this email address')
            ],
            [
                'email',
                function ($attribute) {
                    $this->user = $this->finder->findUserByEmail($this->email);
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
