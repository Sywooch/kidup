<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "mail_account".
 *
 * @property string $name
 * @property integer $user_id
 * @property integer $conversation_id
 * @property integer $created_at
 *
 * @property \app\models\Conversation $conversation
 * @property \app\models\User $user
 * @property \app\models\MailMessage[] $mailMessages
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
            [['name'], 'string', 'max' => 128],
            [['conversation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Conversation::className(), 'targetAttribute' => ['conversation_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']]
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
    public function getConversation()
    {
        return $this->hasOne(\app\models\Conversation::className(), ['id' => 'conversation_id']);
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
    public function getMailMessages()
    {
        return $this->hasMany(\app\models\MailMessage::className(), ['mail_account_name' => 'name']);
    }




}
