<?php

namespace admin\models;

use Yii;

/**
 * This is the base-model class for table "log".
 *
 * @property integer $id
 * @property string $language
 * @property string $translation
 *
 * @property I18nSource $i18nSource
 */
class I18nMessage extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'i18n_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['language', 'translation'], 'string'],
            [['id'], 'integer'],
            [['language'], 'string', 'max' => 5],
            [['translation'], 'string', 'max' => 2048],
        ];
    }

    public function getI18nSource(){
        return $this->hasOne(I18nSource::className(), ['id' => 'id']);
    }

    public static function getDb(){
        if(YII_CONSOLE){
            return Yii::$app->dbaws;
        }else{
            return Yii::$app->db;
        }
    }
}
