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
         * @var \app\modules\mail\models\MailAccount $account ;
         */

        $senderId = $account->user_id == $account->conversation->initiater_user_id ? $account->conversation->target_user_id : $account->conversation->initiater_user_id;

        // see if already exists
        $m = Message::find()->where([
            'conversation_id' => $account->conversation_id,
            'message' => $postData['text'],
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
            'message' => $postData['text'],
            'sender_user_id' => $senderId,
            'receiver_user_id' => $account->user_id,
        ]);
        $m->save();

        $mm = new MailMessage();
        $mm->setAttributes([
            'message' => $postData['text'],
            'from_email' => $postData['from'],
            'subject' => $postData['subject'],
            'created_at' => time(),
            'mail_acount_name' => str_replace("@reply.kidup.dk", '', $postData['to']),
            'message_id' => $m->id
        ]);

        return $mm->save();
    }
}
