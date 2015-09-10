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
 * @property \app\models\Booking[] $bookings
 * @property \app\models\Currency $currency
 * @property \app\models\Invoice $invoice
 * @property \app\models\User $user
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
            [['nonce'], 'string', 'max' => 1024],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']]
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
        return $this->hasMany(\app\models\Booking::className(), ['payin_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(\app\models\Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(\app\models\Invoice::className(), ['id' => 'invoice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
    }




}
