<?php

namespace api\v2\models;

/**
 * This is the model class for table "item".
 */
class Currency extends \user\models\currency\Currency
{
    public function fields()
    {
        $fields = parent::fields();

        return $fields;
    }

}
