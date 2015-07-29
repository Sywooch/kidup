<?php

namespace app\models\base;

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
            'id' => Yii::t('app', 'ID'),
            'data' => Yii::t('app', 'Data'),
            'email' => Yii::t('app', 'Email'),
            'type' => Yii::t('app', 'Type'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
}
