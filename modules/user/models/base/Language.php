<?php

namespace user\models\base;

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
 * @property \user\models\Country[] $countries
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
            'language_id' => 'Language ID',
            'language' => 'Language',
            'name' => 'Name',
            'name_ascii' => 'Name Ascii',
            'status' => 'Status',
            'country' => 'Country',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(\user\models\Country::className(), ['main_language_id' => 'language_id']);
    }
}
