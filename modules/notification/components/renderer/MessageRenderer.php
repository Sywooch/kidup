<?php
namespace notification\components\renderer;

use message\models\message\Message;
use notification\components\Renderer;

class MessageRenderer
{

    /**
     * Load a conversation.
     *
     * @param Message $conversation The conversation.
     * @return array All the render variables.
     */
    public function loadMessage(Message $message) {
        $result = [];
        $result['sender_name'] = $message->conversation->targetUser->profile->getName();
        return $result;
    }

}