<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "currency".
 *
 * @property integer $id
 * @property string $name
 * @property string $abbr
 * @property string $forex_name
 *
 * @property \app\models\base\Booking[] $bookings
 * @property \app\models\base\Country[] $countries
 * @property \app\models\base\Item[] $items
 * @property \app\models\base\Payin[] $payins
 * @property \app\models\base\Payout[] $payouts
 * @property \app\models\base\Profile[] $profiles
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
        return $this->hasMany(\app\models\base\Booking::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(\app\models\base\Country::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\app\models\base\Item::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayins()
    {
        return $this->hasMany(\app\models\base\Payin::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayouts()
    {
        return $this->hasMany(\app\models\base\Payout::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(\app\models\base\Profile::className(), ['currency_id' => 'id']);
    }
}
