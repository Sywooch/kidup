<?php

namespace message\models\conversation;

use app\components\behaviors\UtfEncodeBehavior;
use app\components\Exception;
use message\models\message\Message;
use notification\models\MailAccount;
use user\models\user\User;
use Yii;


class ConversationException extends Exception
{
}

/**
 * This is the model class for table "conversation".
 */
class Conversation extends ConversationBase
{
    public function behaviors()
    {
        return [
            [
                'class' => UtfEncodeBehavior::className(),
                'attributes' => ['title']
            ]
        ];
    }
    
    public function afterSave($insert, $ca)
    {
        if ($insert == true) {
            (new MailAccount())->createForConversation($this);
        }
        return parent::afterSave($insert, $ca);
    }


    public function getUnreadMessageCount(User $user = null)
    {
        return Message::find()->where([
            'receiver_user_id' => \Yii::$app->user->id,
            'conversation_id' => $this->id,
            'read_by_receiver' => 0
        ])->count();
    }

    public function isOwner()
    {
        return \Yii::$app->user->id == $this->initiater_user_id ||
        \Yii::$app->user->id == $this->target_user_id;
    }
}
