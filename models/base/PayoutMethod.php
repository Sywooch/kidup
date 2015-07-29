<?php

namespace app\models\base;

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
            'id' => Yii::t('app', 'ID'),
            'address' => Yii::t('app', 'Address'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'payee_name' => Yii::t('app', 'Payee Name'),
            'country_id' => Yii::t('app', 'Country ID'),
            'type' => Yii::t('app', 'Type'),
            'user_id' => Yii::t('app', 'User ID'),
            'identifier_1' => Yii::t('app', 'Identifier 1'),
            'identifier_2' => Yii::t('app', 'Identifier 2'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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
    public function getCountry()
    {
        return $this->hasOne(\app\models\base\Country::className(), ['id' => 'country_id']);
    }
}
