<?php

namespace mail\models\base;

use Yii;

/**
 * This is the base-model class for table "mail_account".
 *
 * @property string $name
 * @property integer $user_id
 * @property integer $conversation_id
 * @property integer $created_at
 *
 * @property \user\models\base\User $user
 * @property \message\models\base\Conversation $conversation
 */
class MailAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'user_id', 'conversation_id'], 'required'],
            [['user_id', 'conversation_id', 'created_at'], 'integer'],
            [['name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'user_id' => Yii::t('app', 'User ID'),
            'conversation_id' => Yii::t('app', 'Conversation ID'),
            'created_at' => Yii::t('app', 'Created At'),
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
    public function getConversation()
    {
        return $this->hasOne(\message\models\base\Conversation::className(), ['id' => 'conversation_id']);
    }
}
