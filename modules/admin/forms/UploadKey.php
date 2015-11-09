<?php

namespace admin\forms;

use admin\models\I18nMessage;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * This is the model class for table "category".
 */
class UploadKey extends Model
{
    /**
     * @var UploadedFile
     */
    public $keyFile;
    public $contents;

    public function rules()
    {
        return [
            [['keyFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'key'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->contents = file_get_contents($this->keyFile->tempName);
            return true;
        } else {
            return false;
        }
    }
}
