<?php

namespace user\models;

use Yii;

class UserReferredUser extends \user\models\base\UserReferredUser
{
    /**
     * Creates a new referral link
     * @param User $user the user that is referring
     * @param string $code the referring code
     * @return bool
     */
    public function userIsReferredByUser(User $user, $code){
        $referringUser = User::find()->where(['referral_code' => $code])->one();
        if($referringUser === null){
            Yii::error("User with referring code not found.");
            return false;
        }
        $uru = new UserReferredUser();
        $uru->referred_user_id = $user->id;
        $uru->referring_user_id = $referringUser->id;
        return $uru->save();
    }

    public function beforeValidate()
    {
        if($this->isNewRecord){
            $this->created_at = time();
        }
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }
}
