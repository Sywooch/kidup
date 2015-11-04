<?php

namespace api\models;

/**
 * This is the model class for table "item".
 */
class Review extends \review\models\Review
{
    public function fields()
    {
        if($this->is_public == 0){
            return [];
        }
        return parent::fields();
    }
}
