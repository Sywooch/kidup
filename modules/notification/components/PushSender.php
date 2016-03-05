<?php
namespace notification\components;

use message\components\MobilePush;
use notification\models\base\MobileDevices;
use Swift_Message;
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

        // Send a message to the user
        $userId = $renderer->getUserId();
        $devices = MobileDevices::find()->where(['user_id' => $userId, 'is_subscribed' => true])->all();
        foreach ($devices as $device) {
            $arn = $device->endpoint_arn;
            (new MobilePush())->sendMessage($arn, $view, $parameters);
        }
    }

}