<?php
namespace mail\mails\message;

use notifications\models\MailAccount;
use notifications\models\UrlFactory;
use message\models\Message;


/**
 */
class NewMessageFactory
{
    public function create(Message $message)
    {
        $mail = new NewMessage();
        $mail->setSubject('New message');
        $mail->conversationUrl = UrlFactory::conversation($message->conversation);

        $mail->emailAddress = $message->receiverUser->email;

        $mail->otherUserName = $message->senderUser->profile->getFullName();

        $mailAccount = MailAccount::find()->where([
            'conversation_id' => $message->conversation_id,
            'user_id' => $message->sender_user_id
        ])->one();
        $mail->otherUserName = '';
        if ($mailAccount !== null) {
            $mail->setSender($mailAccount->name . "@reply.kidup.dk");
            $mail->otherUserName = $message->senderUser->profile->first_name;
        } else {
            $mail->setSender("info@kidup.dk");
        }

        return $mail;
    }
}