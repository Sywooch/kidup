<?php

namespace user\models\token;

use Yii;

/**
 * This is the base-model class for table "token".
 *
 * @property integer $user_id
 * @property string $code
 * @property integer $type
 * @property integer $created_at
 *
 * @property \user\models\user\User $user
 */
class TokenBase extends \app\components\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'code', 'type', 'created_at'], 'required'],
            [['user_id', 'type', 'created_at'], 'integer'],
            [['code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' =>  'User ID',
            'code' =>  'Code',
            'type' =>  'Type',
            'created_at' =>  'Created At',
        ];
    }


    /** @inheritdoc */
    public static function primaryKey()
    {
        return ['user_id', 'code', 'type'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\user\models\user\User::className(), ['id' => 'user_id']);
    }
}
