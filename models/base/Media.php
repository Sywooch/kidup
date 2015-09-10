<?php

namespace app\models\base;

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
 * @property \app\models\ItemHasMedia[] $itemHasMedia
 * @property \app\models\Item[] $items
 * @property \app\models\User $user
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
            [['file_name'], 'string', 'max' => 128],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'storage' => Yii::t('app', 'Storage'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'file_name' => Yii::t('app', 'File Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemHasMedia()
    {
        return $this->hasMany(\app\models\ItemHasMedia::className(), ['media_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\app\models\Item::className(), ['id' => 'item_id'])->viaTable('item_has_media', ['media_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
    }




}
