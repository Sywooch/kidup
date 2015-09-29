<?php

namespace user\models\base;

use Yii;

/**
 * This is the base-model class for table "currency".
 *
 * @property integer $id
 * @property string $name
 * @property string $abbr
 * @property string $forex_name
 *
 * @property \booking\models\base\Booking[] $bookings
 * @property \user\models\base\Country[] $countries
 * @property \item\models\base\Item[] $items
 * @property \booking\models\base\Payin[] $payins
 * @property \booking\models\base\Payout[] $payouts
 * @property \user\models\base\Profile[] $profiles
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'abbr', 'forex_name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['abbr', 'forex_name'], 'string', 'max' => 5]
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
            'abbr' => Yii::t('app', 'Abbr'),
            'forex_name' => Yii::t('app', 'Forex Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(\booking\models\base\Booking::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(\user\models\base\Country::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\item\models\base\Item::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayins()
    {
        return $this->hasMany(\booking\models\base\Payin::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayouts()
    {
        return $this->hasMany(\booking\models\base\Payout::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(\user\models\base\Profile::className(), ['currency_id' => 'id']);
    }
}
