<?php
namespace notification\components;

use message\components\MobilePush;
use notification\models\base\MobileDevices;
use Swift_Message;
use Yii;
use yii\swiftmailer\Mailer;

class PushSender
{
    /**
     * Send a push message.
     *
     * @param $renderer
     * @return bool Whether the mail was sent succesfully.
     */
    public static function send(PushRenderer $renderer)
    {
        $view = $renderer->render();

        /*$parameters = [
            'state' => 'app.create-booking',
            'params' => [
                'itemId' => 6
            ]
        ];*/
        $parameters = [
            'state' => 'app.location'
        ];

        // Do some test magic
        if (\Yii::$app->modules['notification']->useFileTransfer) {
            $path = \Yii::$aliases['@runtime'] . '/notification/';
            if (!is_dir($path)) {
                mkdir($path);
            }
            file_put_contents($path . 'push.view', $view);
            file_put_contents($path . 'push.params', json_encode($parameters));
            return true;
        }

        // Send a message to the user
        $userId = $renderer->getUserId();
        $devices = MobileDevices::find()->where(['user_id' => $userId, 'is_subscribed' => true])->all();
        foreach ($devices as $device) {
            $arn = $device->endpoint_arn;
            (new MobilePush())->sendMessage($arn, $view, $parameters);
        }
    }

}