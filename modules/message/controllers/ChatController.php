<?php
namespace app\modules\message\controllers;

use app\controllers\Controller;
use app\modules\message\forms\ChatMessage;
use app\modules\message\models\Conversation;
use app\modules\message\models\Message;
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
                'only' => [ 'index' ],
                'rules' => [
                    [
                        // allow only authenticated
                        'allow' => true,
                        'actions' => [ 'index' ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($id = null)
    {
        if ($id == null) {
            $c = Conversation::find()->orWhere(['initiater_user_id' => \Yii::$app->user->id])
                ->orWhere(['target_user_id' => \Yii::$app->user->id])
                ->joinWith(['lastMessage'])
                ->orderBy('message.created_at DESC')
                ->one();
            if ($c == null) {
                \Yii::$app->session->addFlash('info', \Yii::t('message', "You don't have any conversations yet."));
                return $this->redirect('@web/home');
            }else{
                return $this->redirect('@web/messages/'.$c->id);
            }
        } else {
            $c = Conversation::find()->where(['conversation.id' => $id])->one();
            if ($c->initiater_user_id !== \Yii::$app->user->id && $c->target_user_id !== \Yii::$app->user->id) {
                throw new ForbiddenHttpException("Conversation doesn't belong to you");
            }
            Message::updateAll(['read_by_receiver' => 1],
                ['conversation_id' => $id, 'receiver_user_id' => \Yii::$app->user->id]);
            if ($c == null) {
                throw new NotFoundHttpException("Conversation not found");
            }
        }
        if ($c->initiater_user_id !== \Yii::$app->user->id && $c->target_user_id !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }

        $form = new ChatMessage();

        if (Yii::$app->request->isPost && $form->load($_POST) && $form->validate()) {
            $form->save($c);
        }
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

        $m = Message::find()->where(['conversation_id' => $id])->orderBy('created_at DESC')->all();

        $booking = $c->booking ?: false;

        return $this->render('chat', [
            'conversation' => $c,
            'booking' => $booking,
            'form' => $form,
            'messages' => $m,
            'conversationDataProvider' => $dataProvider
        ]);
    }
}
