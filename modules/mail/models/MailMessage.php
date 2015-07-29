<?php

namespace app\modules\mail\models;

use app\modules\message\models\Message;
use Carbon\Carbon;
use League\Flysystem\Filesystem;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "mail_message".
 */
class MailMessage extends \app\models\base\MailMessage
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => function () {
                    return Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
                }
            ],
        ];
    }

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

    public function parseInbox()
    {
        if(is_file('/var/mail/root')){
            if(!is_dir(\Yii::$aliases['@runtime'].'/received-email/')) {
                mkdir(\Yii::$aliases['@runtime'] . '/received-email/');
            }
            $newFileName = \Yii::$aliases['@runtime'].'/received-email/'.time();
            rename('/var/mail/root', $newFileName);
        }else{
            if(YII_DEBUG && is_file(\Yii::$aliases['@runtime'].'/root')){
                $newFileName = \Yii::$aliases['@runtime'].'/root';
            }else{
                return false;
            }
        }
        $mbox = new \ezcMailMboxTransport($newFileName);
        $set = $mbox->fetchAll();
        // Create a new mail parser object
        $parser = new \ezcMailParser();
        // Parse the set of messages retrieved from the mbox file earlier
        $mail = $parser->parseMail($set);
        foreach ($mail as $email) {
            $this->parseMail($email);
        }
    }

    private function parseMail($email)
    {
        // find the mail account
        // find reply @ kidup.dk email
        foreach ($email->to as $to) {
            if (strpos($to->email, '@reply.kidup.dk') > 0) {
                $account = MailAccount::findOne(['name' => str_replace('@reply.kidup.dk', '', $to->email)]);
                if ($account == null) {
                    \Yii::$app->clog->notice($to->email . " email address not found in db");

                    return false;
                }
            }
        }
        if(!isset($account)) return false;


        // find sender user id
        $senderId = $account->user_id == $account->conversation->initiater_user_id ? $account->conversation->target_user_id : $account->conversation->initiater_user_id;

        // see if already exists
        $m = Message::find()->where([
            'conversation_id' => $account->conversation_id,
            'message' => $email->fetchParts()[0]->text,
            'sender_user_id' => $senderId,
            'receiver_user_id' => $account->user_id,
        ])->one();

        if (count($m) > 0) {
            return false;
        }

        // create the message
        $m = new Message();
        $m->setAttributes([
            'conversation_id' => $account->conversation_id,
            'message' => $email->fetchParts()[0]->text,
            'sender_user_id' => $senderId,
            'receiver_user_id' => $account->user_id,
        ]);
        $m->save();

        $mm = new MailMessage();
        $mm->setAttributes([
            'message' => $email->fetchParts()[0]->text,
            'from_email' => $email->from->email,
            'subject' => $email->subject,
            'created_at' => time(),
            'mail_account_name' => $account->name,
            'message_id' => $m->id
        ]);

        return $mm->save();
    }
}
