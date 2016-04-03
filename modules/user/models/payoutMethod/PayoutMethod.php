<?php

namespace user\models\payoutMethod;

use Yii;

class PayoutMethod extends PayoutMethodBase
{
    const TYPE_DK_KONTO = 'dk-konto';

    /**
     * Checks if user has access
     * @param bool $user
     * @return bool
     */
    public function userHasAccess($user = false){
        $user = $user ? $user : \Yii::$app->user;
        return $user->id === $this->user_id;
    }

    public function transformToSafe($input, $leaveUntouched = 4)
    {
        $length = strlen($input);
        $input = substr($input, $length - $leaveUntouched);
        return str_repeat("*", $length - $leaveUntouched) . $input;
    }
}
