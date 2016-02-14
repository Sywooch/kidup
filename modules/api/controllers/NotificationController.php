<?php
namespace api\controllers;

use api\models\Message;
use message\components\MobilePush;
use Aws;

class NotificationController extends Controller
{
    public function init(){
        $this->modelClass = Message::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['register', 'subscribe-to-topics', 'list-topics'],
            'user' => []
        ];
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['delete']);
//        unset($actions['view']);
//        unset($actions['index']);
        unset($actions['update']);
        unset($actions['create']);
        return $actions;
    }

    public function actionRegister() {
        $params = \Yii::$app->getRequest()->getBodyParams();
        (new MobilePush())->registerDevice($params['device_id'], $params['token'], $params['platform']);
    }

    public function actionSubscribeToTopics() {
        $topics = ['*'];
        $endpointArn = 'arn:aws:sns:eu-central-1:450009623300:endpoint/GCM/Android/d88eeda5-11ad-3909-96bd-12929721a197';

        if (count($topics) == 1 && reset($topics) == '*') {
            $existingTopics = (new MobilePush())->getTopics();
            $topics = [];
            foreach ($existingTopics as $topic) {
                $topics[] = $topic['TopicArn'];
            }
        }

        (new MobilePush())->subscribeToTopics($endpointArn, $topics);
    }

    public function actionListTopics() {
        return (new MobilePush())->getTopics();
    }

}