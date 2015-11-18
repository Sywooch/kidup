<?php
namespace mail\mails\conversation;

use mail\mails\MailUserFactory;
use mail\models\UrlFactory;
use yii\helpers\Url;

/**
 * New message.
 */
class NewMessageFactory
{

    public function create(\message\models\Message $message)
    {
        $receiver = (new MailUserFactory())->createForUser($message->receiverUser);
        $sender = (new MailUserFactory())->createForUser($message->senderUser);

        $e = new NewMessage();
        $e->setReceiver($receiver);
        $e->setSender($sender);
        $e->senderName = $message->senderUser->profile->first_name;
        $e->profileName = $message->receiverUser->profile->first_name;
        $e->title = $message->conversation->title;
        $e->message = $message->message;
        $e->chatUrl = UrlFactory::chat($message->conversation);

        return $e;
    }

}