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
 * @property \user\models\base\Language $mainLanguage
 * @property \item\models\base\Location[] $locations
 * @property \booking\models\base\PayoutMethod[] $payoutMethods
 * @property \user\models\base\Profile[] $profiles
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
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'main_language_id' => Yii::t('app', 'Main Language ID'),
            'currency_id' => Yii::t('app', 'Currency ID'),
            'phone_prefix' => Yii::t('app', 'Phone Prefix'),
            'vat' => Yii::t('app', 'Vat'),
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
        return $this->hasOne(\user\models\base\Language::className(), ['language_id' => 'main_language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(\item\models\base\Location::className(), ['country' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayoutMethods()
    {
        return $this->hasMany(\booking\models\base\PayoutMethod::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(\user\models\base\Profile::className(), ['nationality' => 'id']);
    }
}
