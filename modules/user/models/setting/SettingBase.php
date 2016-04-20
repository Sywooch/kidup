<?php

namespace user\models\setting;

use Yii;

/**
 * This is the base-model class for table "setting".
 *
 * @property integer $id
 * @property string $type
 * @property string $value
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \user\models\user\User $user
 */
class SettingBase extends \app\components\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['type'], 'string', 'max' => 50],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'value' => 'Value',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\user\models\user\User::className(), ['id' => 'user_id']);
    }
}
