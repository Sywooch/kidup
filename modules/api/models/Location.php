<?php

namespace api\models;

/**
 * This is the model class for table "item".
 */
class Location extends \item\models\location\Location
{

    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['created_at'], $fields['updated_at'], $fields['type']);

        if(!$this->canUserAccessDetails()){
            unset($fields['street_number']);
            $this->setLocationEstimation();
            $fields['estimation_radius'] = function($model){
                return  $this->estimationRadius;
            };
        }

        return $fields;
    }
    public function beforeValidate()
    {
        if($this->isNewRecord){
            $this->user_id = \Yii::$app->user->id;
        }
        return parent::beforeValidate();
    }
}
