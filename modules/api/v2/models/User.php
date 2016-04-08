<?php

namespace api\v2\models;

use images\components\ImageHelper;
use review\models\Review;

/**
 * This is the model class for table "item".
 */
class User extends \user\models\user\User
{

    public function extraFields()
    {
        return ['item'];
    }

    public function getItems()
    {
        return $this->hasOne(Item::className(), ['id' => 'owner_id'])->where(['is_available' => 1]);
    }
}
