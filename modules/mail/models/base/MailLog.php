<?php

namespace mail\models\base;

use Yii;

/**
 * This is the base-model class for table "mail_log".
 *
 * @property string $id
 * @property string $data
 * @property string $email
 * @property string $type
 * @property integer $created_at
 */
class MailLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'email', 'type', 'created_at'], 'required'],
            [['data'], 'string'],
            [['created_at'], 'integer'],
            [['id', 'email'], 'string', 'max' => 256],
            [['type'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => 'Data',
            'email' => 'Email',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }
}
