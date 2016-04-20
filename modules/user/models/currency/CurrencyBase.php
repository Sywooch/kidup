<?php

namespace user\models\currency;

use Yii;

/**
 * This is the base-model class for table "currency".
 *
 * @property integer $id
 * @property string $name
 * @property string $abbr
 * @property string $forex_name
 *
 * @property \booking\models\booking\Booking[] $bookings
 * @property \user\models\country\Country[] $countries
 * @property \item\models\item\Item[] $items
 * @property \booking\models\payin\Payin[] $payins
 * @property \booking\models\payout\PayoutBase[] $payouts
 * @property \user\models\profile\Profile[] $profiles
 */
class CurrencyBase extends \app\components\models\BaseActiveRecord
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
            'id' => 'ID',
            'name' => 'Name',
            'abbr' => 'Abbr',
            'forex_name' => 'Forex Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(\booking\models\booking\Booking::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(\user\models\country\Country::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\item\models\item\Item::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayins()
    {
        return $this->hasMany(\booking\models\payin\Payin::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayouts()
    {
        return $this->hasMany(\booking\models\payout\PayoutBase::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(\user\models\profile\Profile::className(), ['currency_id' => 'id']);
    }
}
