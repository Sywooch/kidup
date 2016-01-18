<?php

namespace message\models\base;

use Yii;

/**
 * This is the base-model class for table "mobile_device".
 *
 * @property integer $id
 * @property string $device_id
 * @property string $platform
 * @property string $token
 * @property string $meta_information
 * @property string $user_id
 * @property integer $updated_at
 * @property integer $created_at
 */
class Notification extends \yii\db\ActiveRecord
{

    const PLATFORM_GCM = 'gcm';
    const PLATFORM_APNS = 'apns';

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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => 'Device ID',
            'token' => 'Device token',
            'platform' => 'Messaging platform',
            'user_id' => 'User ID',
            'meta_information' => 'Meta information',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }


}
