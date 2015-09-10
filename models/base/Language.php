<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "language".
 *
 * @property string $language_id
 * @property string $language
 * @property string $name
 * @property string $name_ascii
 * @property string $status
 * @property string $country
 *
 * @property \app\models\base\Country[] $countries
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'language', 'name', 'name_ascii', 'status', 'country'], 'required'],
            [['language_id'], 'string', 'max' => 5],
            [['language', 'country'], 'string', 'max' => 3],
            [['name', 'name_ascii'], 'string', 'max' => 32],
            [['status'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'language_id' => Yii::t('app', 'Language ID'),
            'language' => Yii::t('app', 'Language'),
            'name' => Yii::t('app', 'Name'),
            'name_ascii' => Yii::t('app', 'Name Ascii'),
            'status' => Yii::t('app', 'Status'),
            'country' => Yii::t('app', 'Country'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(\app\models\base\Country::className(), ['main_language_id' => 'language_id']);
    }
}
