<?php

/*
 * This file is part of the app\modules project.
 *
 * (c) app\modules project <http://github.com/app\modules/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\user\forms;

use app\modules\user\models\Profile;
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
            'firstName'   => \Yii::t('user', 'First Name'),
            'lastName'   => \Yii::t('user', 'Last Name'),
            'language'   => \Yii::t('user', 'Language'),
            'description'   => \Yii::t('user', 'Description'),
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

    public function __construct(){
        $profile = Profile::find()->where(['user_id' => \Yii::$app->user->id])->one();
        $this->firstName = $profile->first_name;
        $this->lastName = $profile->last_name;
        $this->language = $profile->language;
        $this->description = $profile->description;

        return parent::__construct();
    }

    public function save(){
        if(!$this->validate()){
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
