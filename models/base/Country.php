<?php

namespace app\models\base;

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
 * @property \app\models\base\Language $mainLanguage
 * @property \app\models\base\Currency $currency
 * @property \app\models\base\Location[] $locations
 * @property \app\models\base\PayoutMethod[] $payoutMethods
 * @property \app\models\base\Profile[] $profiles
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
            [['name', 'code', 'main_language_id', 'currency_id', 'phone_prefix'], 'required'],
            [['currency_id', 'phone_prefix'], 'integer'],
            [['vat'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 2],
            [['main_language_id'], 'string', 'max' => 5],
            [['main_language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['main_language_id' => 'language_id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']]
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
    public function getMainLanguage()
    {
        return $this->hasOne(\app\models\base\Language::className(), ['language_id' => 'main_language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(\app\models\base\Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(\app\models\base\Location::className(), ['country' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayoutMethods()
    {
        return $this->hasMany(\app\models\base\PayoutMethod::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(\app\models\base\Profile::className(), ['nationality' => 'id']);
    }




}
