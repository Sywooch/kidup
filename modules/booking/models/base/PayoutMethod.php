<?php

namespace booking\models\base;

use Yii;

/**
 * This is the base-model class for table "payout_method".
 *
 * @property integer $id
 * @property string $address
 * @property string $bank_name
 * @property string $payee_name
 * @property integer $country_id
 * @property string $type
 * @property integer $user_id
 * @property string $identifier_1
 * @property string $identifier_2
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \app\models\User $user
 * @property \app\models\Country $country
 */
class PayoutMethod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payout_method';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank_name', 'payee_name', 'country_id', 'type', 'user_id', 'identifier_1', 'identifier_2', 'created_at', 'updated_at'], 'required'],
            [['country_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['address', 'type', 'identifier_1', 'identifier_2'], 'string', 'max' => 45],
            [['bank_name', 'payee_name'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('payout_method.attributes.id', 'ID'),
            'address' => Yii::t('payout_method.attributes.address', 'Address'),
            'bank_name' => Yii::t('payout_method.attributes.bank_name', 'Bank Name'),
            'payee_name' => Yii::t('payout_method.attributes.payee_name', 'Payee Name'),
            'country_id' => Yii::t('payout_method.attributes.country', 'Country'),
            'type' => Yii::t('payout_method.attributes.type', 'Type'),
            'user_id' => Yii::t('payout_method.attributes.user_id', 'User'),
            'identifier_1' => Yii::t('payout_method.attributes.identifier_1_konto', 'Identifier 1'),
            'identifier_2' => Yii::t('payout_method.attributes.identifier_2_bank', 'Identifier 2'),
            'created_at' => Yii::t('payout_method.attributes.created_at', 'Created At'),
            'updated_at' => Yii::t('payout_method.attributes.updated_at', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\user\models\base\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(\user\models\base\Country::className(), ['id' => 'country_id']);
    }
}
