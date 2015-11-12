<?php
namespace api\controllers;

use api\models\Conversation;
use api\models\Message;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;

class MessageController extends Controller
{
    public function init(){
        $this->modelClass = Message::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['options'],
            'user' => ['create', 'view']
        ];
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        unset($actions['update']);
        unset($actions['create']);
        return $actions;
    }

    public function actionView($id){
        // todo: security check
        return new ActiveDataProvider([
            'query' => Message::find()
                ->where(['conversation_id' => $id])
                ->orderBy('message.created_at ASC')
        ]);
    }

    public function actionCreate(){
        // todo: security check
        $params = \Yii::$app->request->post();
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
        if($m->save()){
            return $m;
        }

        throw new ServerErrorHttpException("Could not be saved");

    }
}