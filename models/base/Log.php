<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "log".
 *
 * @property integer $id
 * @property string $message
 * @property string $data
 * @property string $category
 * @property integer $created_at
 * @property string $session_id
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message', 'data', 'category', 'created_at'], 'required'],
            [['data'], 'string'],
            [['created_at'], 'integer'],
            [['message'], 'string', 'max' => 128],
            [['category'], 'string', 'max' => 45],
            [['session_id'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'message' => Yii::t('app', 'Message'),
            'data' => Yii::t('app', 'Data'),
            'category' => Yii::t('app', 'Category'),
            'created_at' => Yii::t('app', 'Created At'),
            'session_id' => Yii::t('app', 'Session ID'),
        ];
    }


}
