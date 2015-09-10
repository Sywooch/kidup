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
 * @property string $identifier_1
 * @property string $identifier_2
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 * @property string $identifier_1_encrypted
 * @property string $identifier_2_encrypted
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
            [['bank_name', 'payee_name', 'country_id', 'type', 'identifier_1', 'identifier_2', 'created_at', 'updated_at', 'user_id'], 'required'],
            [['country_id', 'created_at', 'updated_at', 'user_id'], 'integer'],
            [['address', 'type', 'identifier_1', 'identifier_2'], 'string', 'max' => 45],
            [['bank_name', 'payee_name'], 'string', 'max' => 256],
            [['identifier_1_encrypted', 'identifier_2_encrypted'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']]
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
            'identifier_1' => Yii::t('app', 'Identifier 1'),
            'identifier_2' => Yii::t('app', 'Identifier 2'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_id' => Yii::t('app', 'User ID'),
            'identifier_1_encrypted' => Yii::t('app', 'Identifier 1 Encrypted'),
            'identifier_2_encrypted' => Yii::t('app', 'Identifier 2 Encrypted'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(\app\models\Country::className(), ['id' => 'country_id']);
    }




}
