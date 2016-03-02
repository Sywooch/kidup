<?php

namespace notification\models\base;

use Yii;

/**
 * This is the base-model class for table "mail_account".
 *
 * @property string $name
 * @property integer $user_id
 * @property integer $conversation_id
 * @property integer $created_at
 *
 * @property \user\models\User $user
 * @property \message\models\Conversation $conversation
 */
class MailAccount extends \app\models\BaseActiveRecord
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
            'name' => 'Name',
            'user_id' => 'User ID',
            'conversation_id' => 'Conversation ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\user\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(\message\models\Conversation::className(), ['id' => 'conversation_id']);
    }
}
