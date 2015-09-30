<?php

namespace item\models\base;

use Yii;

/**
 * This is the base-model class for table "job_queue".
 *
 * @property integer $id
 * @property string $queue
 * @property integer $attempts
 * @property string $data
 * @property integer $status
 * @property integer $created_at
 * @property integer $execution_time
 */
class JobQueue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'job_queue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attempts', 'status', 'created_at', 'execution_time', 'id'], 'integer'],
            [['data'], 'string'],
            [['queue'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'queue' => 'Queue',
            'attempts' => 'Attemps',
            'data' => 'Data',
            'status' => 'Status',
            'created_at' => 'Created At',
            'execution_time' => 'Execution Time',
        ];
    }
}
