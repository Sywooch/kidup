<?php

namespace api\models;

/**
 * This is the model class for table "item".
 */
class Message extends \message\models\message\Message
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['other_user_id'] = function($model){
            /**
             * @var Message $model
             */
            if(\Yii::$app->user->id === $model->sender_user_id){
                return $model->receiver_user_id;
            }else{
                return $model->sender_user_id;
            }
        };

        $fields['is_from_me'] = function($model){
            /**
             * @var Message $model
             */
            if(\Yii::$app->user->id === $model->sender_user_id){
                return true;
            }else{
                return false;
            }
        };

        unset($fields['sender_user_id']);
        unset($fields['receiver_user_id']);
        unset($fields['updated_at']);

        return $fields;
    }
}
