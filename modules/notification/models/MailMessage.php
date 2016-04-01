<?php

namespace notification\models;

use message\models\message\Message;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "mail_message".
 */
class MailMessage extends base\MailMessage
{

    public function rules()
    {
        return [
            [['message', 'from_email', 'mail_account_name'], 'required'],
            [['message'], 'string'],
            [['message_id', 'created_at'], 'integer'],
            [['from_email', 'mail_account_name'], 'string', 'max' => 128],
            [['subject'], 'string', 'max' => 512]
        ];
    }

    public function processSendgridInput($postData)
    {
        if (strpos($postData['to'], '@reply.kidup.dk') > 0) {
            $account = MailAccount::findOne(['name' => str_replace('@reply.kidup.dk', '', $postData['to'])]);
            if ($account == null) {
                \Yii::$app->clog->notice($postData['to'] . " email address not found in db");
                return false;
            }
        }

        if (!isset($account)) {
            return false;
        }

        /**
         * @var MailAccount $account;
         */

        $senderId = $account->user_id == $account->conversation->initiater_user_id ? $account->conversation->target_user_id : $account->conversation->initiater_user_id;

        // make the incomming html safe
        $postData['html'] = \yii\helpers\HtmlPurifier::process($postData['html']);

        // create the message
        $m = new Message();
        $m->setAttributes([
            'conversation_id' => $account->conversation_id,
            'message' => $postData['html'],
            'sender_user_id' => $senderId,
            'receiver_user_id' => $account->user_id,
        ]);
        $m->save();

        $mm = new MailMessage();
        $mm->setAttributes([
            'message' => $postData['html'],
            'from_email' => $postData['from'],
            'subject' => $postData['subject'],
            'created_at' => time(),
            'message_id' => $m->id
        ]);

        $mm->mail_account_name = $account->name;

        if (!$mm->save()) {
            Yii::error('MailMessage not created');
            throw new ServerErrorHttpException("Incomming email not parsed");
        }

        return true;
    }
}
