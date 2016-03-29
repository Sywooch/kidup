<?php

namespace message\models\message;

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
    const EVENT_NEW_MESSAGE = 'new_message';

    public $fromMe;

    public function beforeValidate()
    {
        if ($this->isAttributeChanged('message')) {
            $this->message = \yii\helpers\HtmlPurifier::process($this->message);
        }
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        $this->message = utf8_encode($this->message);
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->message = utf8_decode($this->message);
        parent::afterFind();
    }

    public function afterSave($insert, $changedVars)
    {
        Event::trigger($this, self::EVENT_NEW_MESSAGE);
        parent::afterSave($insert, $changedVars);
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
