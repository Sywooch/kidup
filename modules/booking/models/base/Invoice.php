<?php

namespace booking\models\base;

use booking\models\base\Payin;
use booking\models\base\Payout;
use Yii;

/**
 * This is the base-model class for table "invoice".
 *
 * @property integer $id
 * @property integer $invoice_number
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $status
 *
 * @property Payin[] $payins
 * @property Payout[] $payouts
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_number', 'created_at', 'updated_at'], 'integer'],
            [['data'], 'string'],
            [['status'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('invoice.attributes.id', 'ID'),
            'invoice_number' => Yii::t('invoice.attributes.invoice_number', 'Invoice Number'),
            'data' => Yii::t('invoice.attributes.data', 'Data'),
            'created_at' => Yii::t('invoice.attributes.created_at', 'Created At'),
            'updated_at' => Yii::t('invoice.attributes.updated_at', 'Updated At'),
            'status' => Yii::t('invoice.attributes.status', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayins()
    {
        return $this->hasMany(\booking\models\base\Payin::className(), ['invoice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayouts()
    {
        return $this->hasMany(\booking\models\base\Payout::className(), ['invoice_id' => 'id']);
    }
}
