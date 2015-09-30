<?php

namespace item\models\base;

use Yii;

/**
 * This is the base-model class for table "media".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $storage
 * @property string $type
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $file_name
 *
 * @property \item\models\ItemHasMedia[] $itemHasMedia
 * @property \item\models\Item[] $items
 * @property \user\models\User $user
 */
class Media extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'storage', 'type', 'created_at', 'updated_at', 'file_name'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['storage'], 'string', 'max' => 25],
            [['type'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 256],
            [['file_name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'storage' => 'Storage',
            'type' => 'Type',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'file_name' => 'File Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemHasMedia()
    {
        return $this->hasMany(\item\models\ItemHasMedia::className(), ['media_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\item\models\Item::className(), ['id' => 'item_id'])->viaTable('item_has_media', ['media_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\user\models\User::className(), ['id' => 'user_id']);
    }
}
