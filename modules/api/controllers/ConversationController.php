<?php
namespace api\controllers;

use api\models\Conversation;
use yii\data\ActiveDataProvider;

class ConversationController extends Controller
{
    public function init(){
        $this->modelClass = Conversation::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => [''],
            'user' => ['index', 'view']
        ];
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['update']);
        unset($actions['create']);
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => Conversation::find()->where(['target_user_id' => \Yii::$app->user->id])
                ->orWhere(['initiater_user_id' => \Yii::$app->user->id])
                ->innerJoinWith('lastMessage')
                ->orderBy('message.created_at DESC')
        ]);
    }
}