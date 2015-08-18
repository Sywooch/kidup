<?php
namespace app\modules\mail\mails;

use app\modules\mail\models\MailAccount;
use app\modules\mail\models\Mailer;
use Yii;
use yii\helpers\Url;

class Conversation extends Mailer
{
    /**
     * Reminder for unfinished item
     * @param \app\modules\message\models\Message $message
     * @return bool
     */
    public function newMessage(\app\modules\message\models\Message $message)
    {
        // try and find the correct matching mail_account
        $mailAccount = MailAccount::find()->where([
            'conversation_id' => $message->conversation_id,
            'user_id' => $message->sender_user_id
        ])->one();
        $senderName = '';
        if ($mailAccount !== null) {
            $from = $mailAccount->name . "@reply.kidup.dk";
            $senderName = $message->senderUser->profile->first_name;
        } else {
            $from = "info@kidup.dk";
        }


        return $this->sendMessage([
            'email' => $message->receiverUser->email,
            'subject' => "RE: {$message->conversation->title}",
            'sender' => $from,
            'senderName' => $senderName,
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