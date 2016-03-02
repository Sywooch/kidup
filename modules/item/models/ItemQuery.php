<?php

namespace item\models;

use app\models\BaseQuery;
use Yii;

/**
 * This is the model class for table "item".
 */
class ItemQuery extends BaseQuery
{
    public function available($state = 1)
    {
        if($state == false){
            $state = 0;
        }
        return $this->andWhere(['is_available' => $state]);
    }
}
