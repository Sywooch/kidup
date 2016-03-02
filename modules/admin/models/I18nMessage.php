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

    public static function findCustomMessage($index, $message, $lang){
        if($lang == null){
            $lang = \Yii::$app->language;
        }
        $i18n = I18nMessage::find()->where([
            'i18n_source.category' => $index,
            'i18n_source.message' => $message,
            'language' => $lang
        ])->innerJoinWith('i18nSource')->one();
        if($i18n == null || $i18n->translation == null){
            return \Yii::$app->getI18n()->translate($index, $message, [], $lang);
        }else{
            return $i18n->translation;
        }
    }

}
