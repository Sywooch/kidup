<?php

namespace api\models;

use images\components\ImageHelper;

/**
 * This is the model class for table "item".
 */
class Message extends \message\models\Message
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

        unset($fields['sender_user_id']);
        unset($fields['receiver_user_id']);
        unset($fields['updated_at']);
        unset($fields['created_at']);

        return $fields;
    }

    public function extraFields()
    {
        return ['otherUser'];
    }

    public function getOtherUser(){
        if(\Yii::$app->user->id === $this->sender_user_id){
            return $this->hasOne(User::className(), ['id' => 'receiver_user_id']);
        }else{
            return $this->hasOne(User::className(), ['id' => 'sender_user_id']);
        }
    }
}
