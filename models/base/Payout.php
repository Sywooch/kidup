<?php

namespace app\models\base;

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
 * @property \app\models\base\Booking[] $bookings
 * @property \app\models\base\User $user
 * @property \app\models\base\Currency $currency
 * @property \app\models\base\Invoice $invoice
 */
class Payout extends \yii\db\ActiveRecord
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
            [['status'], 'string', 'max' => 45],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoice_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'amount' => Yii::t('app', 'Amount'),
            'currency_id' => Yii::t('app', 'Currency ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'processed_at' => Yii::t('app', 'Processed At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'invoice_id' => Yii::t('app', 'Invoice ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(\app\models\base\Booking::className(), ['payout_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(\app\models\base\Invoice::className(), ['id' => 'invoice_id']);
    }




}
