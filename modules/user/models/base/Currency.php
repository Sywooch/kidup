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
 * @property \booking\models\booking\Booking[] $bookings
 * @property \user\models\Country[] $countries
 * @property \item\models\item\Item[] $items
 * @property \booking\models\payin\Payin[] $payins
 * @property \booking\models\payout\PayoutBase[] $payouts
 * @property \user\models\Profile[] $profiles
 */
class Currency extends \app\models\BaseActiveRecord
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
     * Gets the currency of a user, or the default if the user is guest or none is set
     * @return Currency|\yii\db\ActiveRecord
     */
    public static function getUserOrDefault(\yii\web\User $user = null){
        $user = is_null($user) || $user->isGuest ? \Yii::$app->user->identity : $user->identity;
        if (isset($user->profile)) {
            if ($user->profile->currency) {
                return $user->profile->currency;
            }
        }
        return self::getDefault();
    }

    public static function getDefault(){
        return Currency::find()->one();
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
        return $this->hasMany(\user\models\Country::className(), ['currency_id' => 'id']);
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
        return $this->hasMany(\user\models\Profile::className(), ['currency_id' => 'id']);
    }
}
