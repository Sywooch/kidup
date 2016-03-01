<?php

namespace user\models\base;

use Yii;

/**
 * This is the base-model class for table "country".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $main_language_id
 * @property integer $currency_id
 * @property integer $phone_prefix
 * @property double $vat
 *
 * @property \user\models\base\Currency $currency
 * @property \user\models\Language $mainLanguage
 * @property \item\models\Location[] $locations
 * @property \user\models\base\PayoutMethod[] $payoutMethods
 * @property \user\models\Profile[] $profiles
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['main_language_id', 'currency_id', 'phone_prefix'], 'required'],
            [['currency_id', 'phone_prefix'], 'integer'],
            [['vat'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 2],
            [['main_language_id'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'main_language_id' => 'Main Language ID',
            'currency_id' => 'Currency ID',
            'phone_prefix' => 'Phone Prefix',
            'vat' => 'Vat',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(\user\models\base\Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainLanguage()
    {
        return $this->hasOne(\user\models\Language::className(), ['language_id' => 'main_language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(\item\models\Location::className(), ['country' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayoutMethods()
    {
        return $this->hasMany(\user\models\base\PayoutMethod::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(\user\models\Profile::className(), ['nationality' => 'id']);
    }
}
