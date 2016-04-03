<?php

namespace booking\models\payout;

use booking\models\booking\Booking;
use booking\models\booking\BookingBase;
use user\models\currency\Currency;
use user\models\user\User;
use Yii;

/**
 * This is the base-model class for table "payout".
 *
 * @property integer $id
 * @property string $status
 * @property double $amount
 * @property integer $currency_id
 * @property integer $user_id
 * @property integer $processed_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $invoice_id
 *
 * @property \booking\models\booking\Booking[] $bookings
 * @property \booking\models\invoice\Invoice $invoice
 * @property Currency $currency
 * @property User $user
 */
class PayoutBase extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payout';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'amount', 'currency_id', 'user_id', 'created_at'], 'required'],
            [['amount'], 'number'],
            [['currency_id', 'user_id', 'processed_at', 'created_at', 'updated_at', 'invoice_id'], 'integer'],
            [['status'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('payout.attributes.id', 'ID'),
            'status' => Yii::t('payout.attributes.status', 'Status'),
            'amount' => Yii::t('payout.attributes.amount', 'Amount'),
            'currency_id' => Yii::t('payout.attributes.currency_id', 'Currency'),
            'user_id' => Yii::t('payout.attributes.user_id', 'User'),
            'processed_at' => Yii::t('payout.attributes.processed', 'Processed At'),
            'created_at' => Yii::t('payout.attributes.created_at', 'Created At'),
            'updated_at' => Yii::t('payout.attributes.updated_at', 'Updated At'),
            'invoice_id' => Yii::t('payout.attributes.invoice_id', 'Invoice'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['payout_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(\booking\models\invoice\Invoice::className(), ['id' => 'invoice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(BookingBase::className(), ['payin_id' => 'id']);
    }
}
