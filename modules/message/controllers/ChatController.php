<?php
namespace message\controllers;

use app\extended\web\Controller;
use message\forms\ChatMessage;
use message\models\conversation\Conversation;
use message\models\message\Message;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class ChatController extends Controller
{
    public $noFooter;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        // allow only authenticated
                        'allow' => true,
                        'actions' => ['inbox', 'conversation'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionInbox()
    {
        $query = Conversation::find()->orWhere(['initiater_user_id' => \Yii::$app->user->id])
            ->orWhere(['target_user_id' => \Yii::$app->user->id])
            ->innerJoinWith('lastMessage')
            ->orderBy('message.created_at');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('inbox', [
            'conversationDataProvider' => $dataProvider
        ]);
    }

    public function actionConversation($id)
    {
        $c = Conversation::findOneOr404($id);
        if ($c->initiater_user_id !== \Yii::$app->user->id && $c->target_user_id !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException("Conversation doesn't belong to you");
        }
        Message::updateAll(['read_by_receiver' => 1],
            ['conversation_id' => $id, 'receiver_user_id' => \Yii::$app->user->id]);
        if ($c == null) {
            throw new NotFoundHttpException("Conversation not found");
        }
        if ($c->initiater_user_id !== \Yii::$app->user->id && $c->target_user_id !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }

        $form = new ChatMessage();

        if (Yii::$app->request->isPost && $form->load($_POST) && $form->validate()) {
            $form->save($c);
        }


        $m = Message::find()->where(['conversation_id' => $id])->orderBy('created_at DESC')->all();

        $booking = $c->booking ?: false;

        return $this->render('chat', [
            'conversation' => $c,
            'booking' => $booking,
            'form' => $form,
            'messages' => $m,
        ]);
    }
}
