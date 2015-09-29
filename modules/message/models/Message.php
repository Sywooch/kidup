<?php

namespace message\models;

use app\helpers\Event;
use Carbon\Carbon;
use Yii;

/**
 * This is the model class for table "conversation".
 */
class Message extends \app\models\base\Message
{
    const EVENT_NEW_MESSAGE = 'new_message';

    public $fromMe;

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
        }
        if ($this->isAttributeChanged('message')) {
            $this->message = \yii\helpers\HtmlPurifier::process($this->message);
        }
        $this->updated_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            Event::trigger($this, self::EVENT_NEW_MESSAGE);
        }
        return parent::beforeSave($insert);
    }
}
