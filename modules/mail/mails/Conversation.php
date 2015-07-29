<?php
namespace app\modules\mail\mails;

use app\modules\mail\models\Mailer;
use Carbon\Carbon;
use Yii;
use yii\helpers\Url;

class Conversation extends Mailer
{
    /**
     * Reminder for unfinished item
     * @param \app\modules\message\models\Message $message
     * @return bool
     */
    public function newMessage($message)
    {
        return $this->sendMessage([
            'email' => $message->receiverUser->email,
            'subject' => "RE: {$message->conversation->title}",
            'type' => self::MESSAGE,
            'params' => [
                'message' => $message->message,
                'profileName' => $message->receiverUser->profile->first_name,
                'senderName' => $message->senderUser->profile->first_name,
                'title' => $message->conversation->title
            ],
            'urls' => [
                'chat' => Url::to('@web/messages/' . $message->conversation_id, true),
            ]
        ]);
    }

}