<?php
namespace api\v1\controllers;

use api\v1\models\Conversation;
use api\v1\models\Message;
use message\models\conversation\ConversationFactory;
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
                ->orderBy('message.created_at ASC'),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
    }

    public function actionMessages($id)
    {
        if ((int)$id != $id) {
            throw new BadRequestHttpException("Id should be an integer!");
        }
        // mark all as read
        Message::updateAll(['read_by_receiver' => 1],
            ['conversation_id' => $id, 'receiver_user_id' => \Yii::$app->user->id]);

        return new ActiveDataProvider([
            'query' => Message::find()
                ->where(['conversation_id' => $id])
                ->orWhere(['sender_user_id' => \Yii::$app->user->id, 'receiver_user_id' => \Yii::$app->user->id])
                ->orderBy('created_at ASC'),
            'pagination' => [
                'pageSize' => 200,
            ],
        ]);
    }

    public function actionUnreadCount()
    {
        return Message::find()->receiverUserId(\Yii::$app->user->id)
            ->andWhere(['read_by_receiver' => 0])
            ->groupBy('conversation_id')
            ->count();
    }

    /**
     * Overwrite the default create method.
     */
    public function actionCreate()
    {
        $params = \Yii::$app->request->getBodyParams();
        $factory = new ConversationFactory();
        return $factory->createForApi($params, $this->modelClass);
    }
}
