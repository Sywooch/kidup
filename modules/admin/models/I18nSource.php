<?php

namespace admin\models;

use Yii;

/**
 * This is the base-model class for table "log".
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 *
 * @property I18nMessage[] $i18nMessages
 */
class I18nSource extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'i18n_source';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'message'], 'required'],
            [['category', 'message'], 'string'],
            [['id'], 'integer'],
            [['category'], 'string', 'max' => 128],
            [['message'], 'string', 'max' => 2048],
        ];
    }


    public function getI18nMessages(){
        return $this->hasMany(I18nMessage::className(), ['id' => 'id']);
    }

    public static function getDb(){
        if(YII_CONSOLE){
            return Yii::$app->dbaws;
        }else{
            return Yii::$app->db;
        }
    }
}
