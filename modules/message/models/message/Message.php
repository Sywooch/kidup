<?php

namespace message\models\message;

use app\components\behaviors\PermissionBehavior;
use app\components\behaviors\PurifierBehavior;
use app\components\behaviors\UtfEncodeBehavior;
use app\components\Permission;
use app\extended\base\Exception;
use app\helpers\Event;
use Yii;

class MessageException extends Exception
{
}

/**
 * This is the model class for table "conversation".
 */
class Message extends MessageBase
{
    public function behaviors()
    {
        return [
            [
                'class' => PermissionBehavior::className(),
                'permissions' => [
                    Permission::ACTION_CREATE => Permission::USER,
                    Permission::ACTION_READ => Permission::OWNER,
                    Permission::ACTION_UPDATE => Permission::OWNER,
                    Permission::ACTION_DELETE => Permission::OWNER,
                ],
            ],
            [
                'class' => PurifierBehavior::className(),
                'textAttributes' => [
                    self::EVENT_BEFORE_VALIDATE => ['message']
                ]
            ],
            [
                'class' => UtfEncodeBehavior::className(),
                'attributes' => ['message']
            ]
        ];
    }

    public function canCreate(self $self)
    {
        return \Yii::$app->user->id == $self->conversation->target_user_id ||
        \Yii::$app->user->id == $self->conversation->initiater_user_id;
    }

    public function isOwner()
    {
        return $this->sender_user_id == \Yii::$app->user->id;
    }

    /**
     * Adds some styling to automated messages
     * @param string $message
     * @return string
     */
    public static function automatedMessageWrapper($message)
    {
        return "<span style='font-weight: 100;font-size: 12px; font-variant: initial; font-style: italic;'>{$message}</span>";
    }
}
