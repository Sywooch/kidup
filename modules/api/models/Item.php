<?php

namespace api\models;

use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;

/**
 * This is the model class for table "item".
 */
class Item extends \item\models\Item
{
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['created_at'], $fields['updated_at'], $fields['min_renting_days']);

        return $fields;
    }

    public function extraFields()
    {
        return ['owner', 'category', 'location', 'currency'];
    }
}
