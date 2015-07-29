<?php

namespace app\modules\splash\forms;

use app\modules\user\models\User;
use yii\base\Model;

class SplashSignup extends Model
{
    public $email;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
        ];
    }

    public function save()
    {
        $user = User::find()->where(['email' => $this->email])->one();
        if ($user == null) {
            $user = new User();
            $user->setScenario('splash');
            $user->email = $this->email;
            $user->status = User::STATUS_SPLASH;
            $user->save();
        } else {
            $this->addError($this->email, 'Mail is already used');
        }
    }
}