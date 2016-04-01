<?php

namespace message\models\message;

use app\components\models\FactoryTrait;
use booking\models\booking\Booking;
use message\models\conversation\Conversation;
use Yii;

/**
 * This is the model class for table "conversation".
 */
class MessageFactory extends Message
{

    use FactoryTrait;

    /**
     * Adds a message to a conversation
     * @param string $message
     * @param Conversation $conversation
     * @param integer $sendingUserId
     * @return Message
     * @throws MessageException
     */
    public function addToConversation($message, Conversation $conversation, $sendingUserId = null)
    {
        $this->sender_user_id = is_null($sendingUserId) ? \Yii::$app->user->id : $sendingUserId;
        $this->message = $message;
        $this->conversation_id = $conversation->id;
        return $this->create();
    }

    public function createInitialBookingMessage($message = null, Conversation $conversation, Booking $booking)
    {
        if ($message = null) {
            $message = Message::automatedMessageWrapper(\Yii::t('booking.create.automated_new_message',
                '{renter_name} made a rent request for {item_name}', [
                    'renter_name' => \Yii::$app->user->identity->profile->getName(),
                    'item_name' => !empty($booking->item->name) ? $booking->item->name : $booking->item_id
                ]));
        }
        return $this->addToConversation($message, $conversation);
    }

    public function beforeValidate()
    {
        $this->read_by_receiver = 0;
        if ($this->sender_user_id === null) {
            $this->sender_user_id = \Yii::$app->user->id;
        }
        $conversation = Conversation::findOneOr404($this->conversation_id);
        $this->receiver_user_id = $conversation->otherUser->id;
        return parent::beforeValidate();
    }
}
