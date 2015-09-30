<?php

namespace user\models\base;

use Yii;

/**
 * This is the base-model class for table "token".
 *
 * @property integer $user_id
 * @property string $code
 * @property integer $type
 * @property integer $created_at
 *
 * @property \user\models\User $user
 */
class Token extends \yii\db\ActiveRecord
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\user\models\User::className(), ['id' => 'user_id']);
    }
}
