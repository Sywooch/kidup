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

use yii\base\Model;
use app\modules\user\models\Profile;
/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class PostRegistrationProfile extends Model
{
    /** @var string User's email or username */
    public $birthday;
    public $firstName;
    public $lastName;
    public $language;


    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'birthday'      => \Yii::t('user', 'Birthday'),
            'firstName'   => \Yii::t('user', 'First Name'),
            'lastName'   => \Yii::t('user', 'Last Name'),
            'language'   => \Yii::t('user', 'Language'),
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['birthday', 'firstName', 'lastName', 'language'], 'string'],
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

        return parent::__construct();
    }

    public function save(){
        if(!$this->validate()){
            return false;
        }
        $profile = Profile::find()->where(['user_id' => \Yii::$app->user->id])->one();
        $profile->birthday = $this->birthday;
        $profile->first_name = $this->firstName;
        $profile->last_name = $this->lastName;
        $profile->language = $this->language;
        return $profile->save();
    }
}
