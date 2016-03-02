<?php

namespace message\models;

use booking\models\Booking;
use Carbon\Carbon;
use notification\models\MailAccount;
use user\models\base\Profile;
use Yii;
use yii\helpers\Json;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "conversation".
 */
class Conversation extends base\Conversation
{
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
        }
        $this->updated_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
        return parent::beforeValidate();
    }

    public function afterSave($insert, $ca)
    {
        if ($insert == true) {
            (new MailAccount())->createForConversation($this);
        }
        return parent::afterSave($insert, $ca);
    }

    public function getLastMessage()
    {
        return $this->hasOne(Message::className(), ['conversation_id' => 'id'])->orderBy('message.created_at DESC');
    }

    public function getOtherUser()
    {
        if (Yii::$app->user->id == $this->target_user_id) {
            $target = "initiater_user_id";
        } else {
            $target = "target_user_id";
        }
        return $this->hasOne(Profile::className(), ['user_id' => $target]);
    }


    public function addMessage($message, $receiverUserId, $senderUserId = null)
    {
        if ($senderUserId == null) {
            $senderUserId = \Yii::$app->user->id;
        }
        $m = new Message();
        $m->message = $message;
        $m->sender_user_id = $senderUserId;
        $m->conversation_id = $this->id;
        $m->read_by_receiver = 0;
        $m->receiver_user_id = $receiverUserId;
        if (!$m->save()) {
            throw new ServerErrorHttpException(Json::encode($m->getErrors()));
        }
        return true;
    }

    public function unreadMessages()
    {
        return Message::find()->where([
            'receiver_user_id' => \Yii::$app->user->id,
            'conversation_id' => $this->id,
            'read_by_receiver' => 0
        ])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['id' => 'booking_id']);
    }
}
