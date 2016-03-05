<?php
namespace api\controllers;

use api\models\Conversation;
use api\models\Message;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

class ConversationController extends Controller
{
    public function init()
    {
        $this->modelClass = Conversation::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => [''],
            'user' => ['index', 'view', 'messages', 'create', 'unread-count']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Conversation::find()->where(['target_user_id' => \Yii::$app->user->id])
                ->orWhere(['initiater_user_id' => \Yii::$app->user->id])
                ->innerJoinWith('lastMessage')
                ->orderBy('message.created_at ASC')
        ]);
    }

    public function actionMessages($id)
    {
        if ((int)$id != $id) {
            throw new BadRequestHttpException("Id should be an integer!");
        }
        return new ActiveDataProvider([
            'query' => Message::find()
                ->where(['conversation_id' => $id])
                ->orWhere(['sender_user_id' => \Yii::$app->user->id, 'receiver_user_id' => \Yii::$app->user->id])
                ->orderBy('created_at ASC')
        ]);
    }

    public function actionUnreadCount()
    {
        return Message::find()->receiverUserId(\Yii::$app->user->id)->groupBy('conversation_id')->count();
    }

    /**
     * Overwrite the default create method.
     */
    public function actionCreate() {
        $params = \Yii::$app->request->getBodyParams();
        $senderId = \Yii::$app->user->id;
        $targetId = $params['target_user_id'];
        $title = @$params['title'];
        $result = Conversation::find()->where(['target_user_id' => $targetId, 'initiater_user_id' => $senderId])->one();
        if ($result == null) {
            // No conversations were found
            $conversation = new Conversation();
            $conversation->title = $title;
            $conversation->target_user_id = $targetId;
            $conversation->initiater_user_id = $senderId;
            $conversation->save();
            return $conversation;
        } else {
            // A conversation was found
            return $result;
        }
    }

}
