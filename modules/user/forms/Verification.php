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

use user\models\Profile;
use user\models\User;
use user\Module;
use yii\base\Model;

/**
 * VerificationForm gets phone country code and phone number and changes them.
 *
 * @property User $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Verification extends Model
{

    /** @var string */
    public $phone_country;
    public $phone_number;
    /** @var Module */
    protected $module;
    /** @var User */
    private $_user;
    /** @var Profile */
    private $_profile;

    /** @inheritdoc */
    public function __construct($user, $profile, $config = [])
    {
        $this->_user = $user;
        $this->_profile = $profile;

        $this->module = \Yii::$app->getModule('user');
        $this->setAttributes([
            'phone_country' => $this->_profile->phone_country,
            'phone_number' => $this->_profile->phone_number,
        ], false);
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['phone_country', 'phone_number'], 'integer'],
            [['phone_number'], 'integer', 'max' => 9999999999, 'min' => 100000],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
        ];
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'verification-form';
    }

    /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $profile = Profile::findOne($this->_user->id);
            $profile->setScenario('settings');
            $profile->phone_country = $this->phone_country;
            $profile->phone_number = $this->phone_number;
            $profile->save();

            return $this->_user->save();
        }

        return false;
    }
}
