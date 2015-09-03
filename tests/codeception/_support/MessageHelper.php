<?php
namespace app\tests\codeception\_support;

use app\modules\message\models\Conversation;
use app\modules\message\models\Message;
use Codeception\Module;

/**
 * Helper used for creating, viewing and deleting messages.
 *
 * Class MessageHelper
 * @package app\tests\codeception\_support
 */
class MessageHelper extends Module
{

    // list of currently created conversations
    private $conversations = [];

    /**
     * Create a message.
     *
     * @param $from user id of the message creator
     * @param $to user id of the message receiver
     * @param $text text of the message
     * @return mixed the message
     */
    public function createMessage($from, $to, $text)
    {
        $time = time();
        $conversation = new Conversation([
            'initiater_user_id' => 2,
            'target_user_id' => 1,
            'title' => 'Welcome to kidup!',
            'created_at' => $time,
            'updated_at' => $time,
            'booking_id' => 0,
        ]);
        $conversation->save();
        $conversation->addMessage($text, $to, $from);
        $this->conversations[] = $conversation->getPrimaryKey();
        return $conversation->getLastMessage()->one();
    }

    /**
     * Clear all conversations made by this helper.
     */
    public function clearConversations()
    {
        foreach ($this->conversations as $conversationID) {
            Message::deleteAll('conversation_id = :conversation_id', [
                'conversation_id' => $conversationID
            ]);
            Conversation::deleteAll('id = :conversation_id', [
                'conversation_id' => $conversationID
            ]);
        }
        $this->conversations = [];
    }

}

?>