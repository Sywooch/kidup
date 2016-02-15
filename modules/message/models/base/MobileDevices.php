<?php

namespace message\models\base;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the base-model class for table "mobile_device".
 *
 * @property integer $id
 * @property string $platform
 * @property string $device_id
 * @property string $token
 * @property string $meta_information
 * @property string $user_id
 * @property bool $is_subscribed
 * @property string $endpoint_arn
 * @property integer $last_activity_at
 * @property integer $created_at
 */
class MobileDevices extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mobile_devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'token', 'platform'], 'required'],
            ['token', 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => 'Device identifier',
            'token' => 'Push token',
            'platform' => 'Device platform',
            'user_id' => 'User ID',
            'meta_information' => 'Meta information',
            'is_subscribed' => 'Subscribed',
            'endpoint_arn' => 'Amazon AWS Endpoint ARN',
            'last_activity_at' => 'Last activity At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'last_activity_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['last_activity_at'],
                ],
            ],
        ];
    }

}
