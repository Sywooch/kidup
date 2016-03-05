<?php

namespace user\models\base;

use Yii;

/**
 * This is the base-model class for table "token".
 *
 * @property integer $referred_user_id
 * @property integer $referring_user_id
 * @property integer $created_at
 *
 * @property \user\models\User $user
 */
class UserReferredUser extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_referred_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['referred_user_id', 'referring_user_id', 'created_at'], 'required'],
            [['referred_user_id', 'referring_user_id', 'created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'referred_user_id' =>  'Referred User ID',
            'referring_user_id' =>  'Referring User ID',
            'created_at' =>  'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferredUser()
    {
        return $this->hasOne(\user\models\User::className(), ['id' => 'referred_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferringUser()
    {
        return $this->hasOne(\user\models\User::className(), ['id' => 'referring_user_id']);
    }
}
