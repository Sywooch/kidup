<?php

namespace item\models\media;

use Yii;

/**
 * This is the base-model class for table "media".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $file_name
 *
 * @property \item\models\itemHasMedia\ItemHasMedia[] $itemHasMedia
 * @property \item\models\item\Item[] $items
 * @property \user\models\user\User $user
 */
class MediaBase extends \app\components\models\BaseActiveRecord
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
            [['user_id', 'created_at', 'updated_at', 'file_name'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
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
        return $this->hasMany(\item\models\itemHasMedia\ItemHasMedia::className(), ['media_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\item\models\item\Item::className(), ['id' => 'item_id'])->viaTable('item_has_media',
            ['media_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\user\models\user\User::className(), ['id' => 'user_id']);
    }
}
