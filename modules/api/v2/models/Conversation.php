<?php

namespace api\v2\models;

/**
 * This is the model class for table "item".
 */
class Conversation extends \message\models\conversation\Conversation
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['other_user_id'] = function ($model) {
            /**
             * @var Conversation $model
             */
            if (\Yii::$app->user->id === $model->initiater_user_id) {
                return $model->target_user_id;
            } else {
                return $model->initiater_user_id;
            }
        };

        unset($fields['target_user_id']);
        unset($fields['initiater_user_id']);

        return $fields;
    }

    public function extraFields()
    {
        return ['otherUser', 'lastMessage'];
    }

    public function getOtherUser()
    {
        if (\Yii::$app->user->id === $this->initiater_user_id) {
            return $this->hasOne(User::className(), ['id' => 'target_user_id']);
        } else {
            return $this->hasOne(User::className(), ['id' => 'initiater_user_id']);
        }
    }

    public function getLastMessage()
    {
        return $this->hasOne(Message::className(), ['conversation_id' => 'id'])->orderBy('message.created_at DESC');
    }

    public function beforeValidate()
    {
        if($this->isNewRecord){
            $this->initiater_user_id = \Yii::$app->user->id;
        }
        return parent::beforeValidate();
    }
}
