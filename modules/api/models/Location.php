<?php

namespace api\models;

/**
 * This is the model class for table "item".
 */
class Location extends \user\models\Location
{
    public function isUser($model, $prop){
        if($model->id === \Yii::$app->user->id){
            return $model->profile->{$prop};
        }
        return '';
    }

    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['created_at'], $fields['updated_at'], $fields['type']);

        return $fields;
    }
}
