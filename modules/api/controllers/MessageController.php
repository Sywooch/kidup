<?php
namespace api\controllers;

use api\models\Conversation;
use api\models\Message;

class MessageController extends Controller
{
    public function init()
    {
        $this->modelClass = Message::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => [],
            'user' => ['create', 'view', 'index', 'options', 'unread-count']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
//        unset($actions['view']);
//        unset($actions['index']);
        unset($actions['update']);
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate()
    {
        // todo: security check
        $params = \Yii::$app->getRequest()->getBodyParams();
        /**
         * @var Conversation $c
         */
        $c = Conversation::findOne(['id' => $params['conversation_id']]);
        $m = new Message();
        $m->receiver_user_id = $c->otherUser->id;
        $m->read_by_receiver = 0;
        $m->sender_user_id = \Yii::$app->user->id;
        $m->message = $params['message'];
        $m->conversation_id = $c->id;
        if ($m->save()) {
            return $m;
        }

        return ("Could not be saved");

    }

}