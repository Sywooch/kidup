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

use user\models\profile\Profile;
use user\models\setting\Setting;
use user\models\user\User;
use user\Module;
use yii\base\Model;

/**
 * SettingsForm gets user's username, email and password and changes them.
 *
 * @property User $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Settings extends Model
{

    /** @var string */
    public $email;
    public $rent_reminder;
    public $message_update;
    public $rent_status_change;
    public $newsletter;
    public $language;
    public $currency_id;
    public $phone_country;
    public $phone_number;
    public $old_password;
    public $new_password;
    /** @var Module */
    protected $module;
    /** @var User */
    private $_user;

    /** @inheritdoc */
    public function __construct($config = [])
    {
        $this->module = \Yii::$app->getModule('user');
        $this->setAttributes([
            'email' => $this->user->unconfirmed_email ?: $this->user->email,
            'language' => $this->user->profile->language,
            'currency_id' => $this->user->profile->currency_id,
            'phone_country' => $this->user->profile->phone_country,
            'phone_number' => $this->user->profile->phone_number,
        ], false);
        $this->rent_reminder = $this->findSetting(Setting::MAIL_BOOKING_REMINDER)->value;
        $this->message_update = $this->findSetting(Setting::MESSAGE_UPDATE)->value;
        $this->rent_status_change = $this->findSetting(Setting::BOOKING_STATUS_CHANGE)->value;
        $this->newsletter = $this->findSetting(Setting::NEWSLETTER)->value;
        parent::__construct($config);
    }

    private function findSetting($type)
    {
        $s = Setting::find()->where(['user_id' => \Yii::$app->user->id, 'type' => $type])->one();
        if ($s == null) {
            $s = new Setting();
            $s->user_id = \Yii::$app->user->id;
            $s->type = $type;
            $s->value = 1;
            $s->save();
        }

        return $s;
    }

    /** @return User */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = \Yii::$app->user->identity;
        }

        return $this->_user;
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['email'], 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            [
                ['email'],
                'unique',
                'when' => function ($model, $attribute) {
                    return $this->user->$attribute != $model->$attribute;
                },
                'targetClass' => User::className()
            ],
            [['rent_reminder', 'message_update', 'rent_status_change', 'newsletter', 'currency_id'], 'required'],
            [['rent_reminder', 'message_update', 'rent_status_change', 'newsletter', 'currency_id'], 'integer'],
            [['phone_country', 'phone_number'], 'integer'],
            [['new_password'], 'string', 'min' => 6],
            [
                ['old_password'],
                function () {
                    if (!\Yii::$app->security->validatePassword($this->old_password,
                        \Yii::$app->user->identity->password_hash)
                    ) {
                        $this->addError('old_password', \Yii::t('user.settings.account.update_password.old_not_correct',
                            "Please fill in your current password here."));
                    }
                }
            ],
            [
                ['old_password'],
                'required',
                'when' => function () {
                    return isset($this->new_password);
                },
            ],
            [
                ['new_password'],
                'required',
                'when' => function () {
                    return isset($this->old_password);
                },
            ],
            [['phone_number'], 'integer', 'max' => 9999999999, 'min' => 100000],
            [['language'], 'string'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email' => \Yii::t('user.attributes.email', 'Email'),
        ];
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'settings-form';
    }

    /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $this->user->scenario = 'settings';
            $this->user->email = $this->email;

            $s = $this->findSetting(Setting::BOOKING_STATUS_CHANGE);
            $s->value = $this->rent_status_change;
            $s->save();

            $s = $this->findSetting(Setting::MAIL_BOOKING_REMINDER);
            $s->value = $this->rent_reminder;
            $s->save();

            $s = $this->findSetting(Setting::MESSAGE_UPDATE);
            $s->value = $this->message_update;
            $s->save();

            $s = $this->findSetting(Setting::NEWSLETTER);
            $s->value = $this->newsletter;
            $s->save();

            $profile = Profile::findOne($this->user->id);
            $profile->setScenario('settings');
            $profile->language = $this->language;
            $profile->currency_id = $this->currency_id;
            $profile->phone_country = $this->phone_country;
            $profile->phone_number = $this->phone_number;

            $profile->save();

            if (isset($this->new_password)) {
                // should all be validated
                $this->user->password_hash = \Yii::$app->security->generatePasswordHash($this->new_password);
            }

            return $this->user->save();
        }

        return false;
    }
}
