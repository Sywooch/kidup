<?php

namespace admin\forms;

use \admin\models\I18nMessage;
use \admin\models\I18nSource;
use DeepCopy\DeepCopy;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "category".
 */
class Translate extends Model
{
    public $translation;
    public $source;
    public $language;
    private $message;

    public function rules()
    {
        return [
            [['translation'], 'string'],
        ];
    }

    public function init(){
        $this->message = I18nMessage::findOne(['id' => $this->source->id, 'language' => $this->language]);
        $this->translation = $this->message->translation;
    }

    public function formName()
    {
        return 'edit-translation';
    }

    public function save()
    {
        if ($this->validate()) {
            $this->message->translation = $this->translation;
            return $this->message->save();
        }

        return false;
    }
}
