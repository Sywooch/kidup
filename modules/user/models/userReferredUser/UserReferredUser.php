<?php

namespace user\models\userReferredUser;

use user\models\user\User;
use yii;

class UserReferredUser extends UserReferredUserBase
{
    /**
     * Creates a new referral link
     * @param User $user the user that is referring
     * @param string $code the referring code
     * @return bool
     */
    public function userIsReferredByUser(User $user, $code)
    {
        $referringUser = User::find()->where(['referral_code' => $code])->one();
        if ($referringUser === null) {
            Yii::error("User with referring code not found.");
            return false;
        }
        $uru = new UserReferredUser();
        $uru->referred_user_id = $user->id;
        $uru->referring_user_id = $referringUser->id;
        return $uru->save();
    }

    /**
     * Returns the list of users with the most referrals
     * @param $since
     * @return array topreferrers array
     */
    public function topList($since = false)
    {
        $topReferrers = UserReferredUser::find()
            ->groupBy('referring_user_id')
            ->select('referring_user_id, count(1) as cnt')
            ->orderBy("cnt DESC")
            ->asArray()
            ->limit(5)
            ->innerJoinWith('referringUser');
        if ($since) {
            $topReferrers->andWhere('created_at > :t');
            $topReferrers->addParams([':t' => $since]);
        }
        $topReferrers = $topReferrers->all();
        $res = [];
        foreach ($topReferrers as $topReferrer) {
            $res[] = [
                'user' => new User($topReferrer['referringUser']),
                'count' => $topReferrer['cnt']
            ];
        }
        return $res;
    }
}
