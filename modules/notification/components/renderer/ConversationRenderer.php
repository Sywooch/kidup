<?php
namespace notification\components\renderer;

use message\models\conversation\Conversation;
use notification\components\Renderer;

class ConversationRenderer
{

    /**
     * Load a conversation.
     *
     * @param Conversation $conversation The conversation.
     * @return array All the render variables.
     */
    public function loadConversation(Conversation $conversation) {
        $result = [];
        return $result;
    }

}