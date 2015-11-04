<?php

namespace mail\models;

use message\models\Conversation;
use Yii;

/**
 * This is the model class for table "mail_account".
 */
class MailAccount extends base\MailAccount
{

    public function createForConversation(Conversation $conversation)
    {
        $initiater = new MailAccount();
        $initiater->setAttributes([
            'user_id' => $conversation->initiater_user_id,
            'conversation_id' => $conversation->id,
            'created_at' => time(),
            'name' => $this->newAddres(),
        ]);
        $initiater->save();

        $target = new MailAccount();
        $target->setAttributes([
            'user_id' => $conversation->target_user_id,
            'conversation_id' => $conversation->id,
            'created_at' => time(),
            'name' => $this->newAddres(),
        ]);
        $target->save();
    }

    private function newAddres()
    {
        $add = md5(\Yii::$app->security->generateRandomString());
        while (MailAccount::find()->where(['name' => $add])->count() > 0) {
            return $this->newAddres();
        }
        return $add;
    }
}
