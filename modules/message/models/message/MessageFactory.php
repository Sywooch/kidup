<?php

namespace message\models\message;

use message\models\conversation\Conversation;
use user\models\User;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "conversation".
 */
class MessageFactory
{
    /**
     * Adds a message to a conversation
     * @param string $message
     * @param Conversation $conversation
     * @param User $sendingUser
     * @return Message
     * @throws MessageException
     */
    public function addMessageToConversation($message, Conversation $conversation, User $sendingUser = null)
    {
        if ($sendingUser == null) {
            $sendingUser = \Yii::$app->user->identity;
        }
        $otherUser = $conversation->target_user_id;
        if($conversation->target_user_id == $sendingUser->id){
            $otherUser = $conversation->initiater_user_id;
        }

        $m = new Message();
        $m->message = $message;
        $m->sender_user_id = $sendingUser->id;
        $m->conversation_id = $conversation->id;
        $m->read_by_receiver = 0;
        $m->receiver_user_id = $otherUser;
        if (!$m->save()) {
            throw new MessageException("Message failed to save".Json::encode($m->getErrors()));
        }
        return $message;
    }
}
