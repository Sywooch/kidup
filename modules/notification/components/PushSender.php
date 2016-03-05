<?php
namespace notification\components;

use notification\models\base\MobileDevices;
use notification\models\TemplateRenderer;
use Swift_Message;
use yii\swiftmailer\Mailer;

class PushSender
{

    /**
     * Send a mail.
     *
     * @param $renderer
     * @return bool Whether the mail was sent succesfully.
     */
    public static function send(TemplateRenderer $renderer)
    {
        $view = $renderer->renderPush();
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
        $userId = $renderer->pushRenderer->getUserId();
        $devices = MobileDevices::find()->where(['user_id' => $userId, 'is_subscribed' => true])->all();
        foreach ($devices as $device) {
            $arn = $device->endpoint_arn;
            (new MobilePush())->sendMessage($arn, $view, $parameters);
        }
    }

}