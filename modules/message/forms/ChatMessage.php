<?php

namespace app\modules\message\forms;

use app\modules\message\models\Message;
use yii\base\Model;

class ChatMessage extends Model
{
    public $message;

    public function formName()
    {
        return 'chat-message';
    }

    public function rules()
    {
        return [
            [['message'], 'string'],
        ];
    }

    public function save($c)
    {
        $m = new Message;
        $m->receiver_user_id = $c->otherUser->user_id;
        $m->read_by_receiver = 0;
        $m->sender_user_id = \Yii::$app->user->id;
        $m->message = \yii\helpers\HtmlPurifier::process($this->message);
        $m->conversation_id = $c->id;
        $m->save();
        $this->message = '';
        return $m;
    }

}