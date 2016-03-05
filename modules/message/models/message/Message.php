<?php

namespace message\models\message;

use app\helpers\Event;
use Yii;

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

    public function afterSave($insert, $changedVars)
    {
        if ($this->isNewRecord) {
            Event::trigger($this, self::EVENT_NEW_MESSAGE);
        }
        parent::afterSave($insert, $changedVars);
    }
}
