<?php

namespace booking\models\booking;


use app\components\models\BaseQuery;
use user\models\user\User;

class BookingQuery extends BaseQuery
{
    public function renter(User $user){
        return $this->andWhere([
            'renter_id' => $user->id
        ]);
    }

    public function owner(User $user){
        return $this->andWhere([
            'item.owner_id' => $user->id
        ])->innerJoinWith('item');
    }
}
