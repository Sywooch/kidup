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

use \user\models\Profile;
use yii\base\Model;

/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 *
 */
class PostRegistrationProfile extends Model
{
    /** @var string User's email or username */
    public $firstName;
    public $lastName;
    public $language;
    public $description;


    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'firstName' => \Yii::t('profile.attributes.first_name', 'First Name'),
            'lastName' => \Yii::t('profile.attributes.last_name', 'Last Name'),
            'language' => \Yii::t('profile.attributes.language', 'Language'),
            'description' => \Yii::t('profile.attributes.description', 'Description'),
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['firstName', 'lastName', 'language', 'description'], 'string'],
        ];
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'post-registration-form';
    }

    public function __construct()
    {
        $profile = Profile::find()->where(['user_id' => \Yii::$app->user->id])->one();
        $this->firstName = $profile->first_name;
        $this->lastName = $profile->last_name;
        $this->language = $profile->language;
        $this->description = $profile->description;

        return parent::__construct();
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $profile = Profile::find()->where(['user_id' => \Yii::$app->user->id])->one();
        $profile->description = $this->description;
        $profile->first_name = $this->firstName;
        $profile->last_name = $this->lastName;
        $profile->language = $this->language;
        return $profile->save();
    }
}
