<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "payin".
 *
 * @property integer $id
 * @property string $data
 * @property string $type
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
 * @property Booking[] $bookings
 * @property Invoice $invoice
 * @property User $user
 * @property Currency $currency
 */
class Payin extends \yii\db\ActiveRecord
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
            [['data', 'braintree_backup'], 'string'],
            [['user_id', 'currency_id'], 'required'],
            [['user_id', 'currency_id', 'created_at', 'updated_at', 'invoice_id'], 'integer'],
            [['amount'], 'number'],
            [['type'], 'string', 'max' => 25],
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
            'id' => Yii::t('app', 'ID'),
            'data' => Yii::t('app', 'Data'),
            'type' => Yii::t('app', 'Type'),
            'user_id' => Yii::t('app', 'User ID'),
            'status' => Yii::t('app', 'Status'),
            'currency_id' => Yii::t('app', 'Currency ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'nonce' => Yii::t('app', 'Nonce'),
            'braintree_backup' => Yii::t('app', 'Braintree Backup'),
            'amount' => Yii::t('app', 'Amount'),
            'invoice_id' => Yii::t('app', 'Invoice ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(\app\models\base\Booking::className(), ['payin_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(\app\models\base\Invoice::className(), ['id' => 'invoice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\base\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(\app\models\base\Currency::className(), ['id' => 'currency_id']);
    }
}
