<?php

namespace app\modules\mail\models;

use app\modules\message\models\Conversation;
use Yii;
use Carbon\Carbon;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "mail_account".
 */
class MailAccount extends \app\models\base\MailAccount
{

    public function createForConversation(Conversation $conversation){
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

    private function newAddres(){
        $add = \Yii::$app->security->generateRandomString();
        $add = str_replace('-', '', $add);
        $add = str_replace('_', '', $add);
        while(MailAccount::find()->where(['name' => $add])->count() > 0){
            $add = \Yii::$app->security->generateRandomString();
            $add = str_replace('-', '', $add);
            $add = str_replace('_', '', $add);
        }
        return $add;
    }
}
