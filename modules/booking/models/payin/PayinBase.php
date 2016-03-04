<?php

namespace booking\models\payin;

use booking\models\invoice\InvoiceBase;
use booking\models\booking\BookingBase;
use user\models\base\Currency;
use user\models\User;
use Yii;

/**
 * This is the base-model class for table "payin".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $status
 * @property integer $currency_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $nonce
 * @property string $braintree_backup
 * @property double $amount
 * @property integer $invoice_id
 *
 * @property \booking\models\booking\Booking $booking
 * @property \booking\models\invoice\InvoiceBase $invoice
 * @property User $user
 * @property Currency $currency
 */
class PayinBase extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['braintree_backup'], 'string'],
            [['user_id', 'currency_id'], 'required'],
            [['user_id', 'currency_id', 'created_at', 'updated_at', 'invoice_id'], 'integer'],
            [['amount'], 'number'],
            [['status'], 'string', 'max' => 45],
            [['nonce'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('payin.attributes.id', 'ID'),
            'user_id' => Yii::t('payin.attributes.user_id', 'User'),
            'status' => Yii::t('payin.attributes.status', 'Status'),
            'currency_id' => Yii::t('payin.attributes.currency', 'Currency'),
            'created_at' => Yii::t('payin.attributes.created_at', 'Created At'),
            'updated_at' => Yii::t('payin.attributes.updated_at', 'Updated At'),
            'nonce' => Yii::t('payin.attributes.security_nonce_braintree', 'Nonce'),
            'braintree_backup' => Yii::t('payin.attributes.braintree_backup', 'Braintree Backup'),
            'amount' => Yii::t('payin.attributes.total_amount', 'Amount'),
            'invoice_id' => Yii::t('payin.attributes.invoice_id', 'Invoice'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasOne(\booking\models\booking\Booking::className(), ['payin_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(InvoiceBase::className(), ['id' => 'invoice_id']);
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
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(BookingBase::className(), ['payin_id' => 'id']);
    }
}
