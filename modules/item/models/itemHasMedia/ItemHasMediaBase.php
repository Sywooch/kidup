<?php

namespace item\models\itemhasMedia;

use Yii;

/**
 * This is the base-model class for table "item_has_media".
 *
 * @property integer $item_id
 * @property integer $media_id
 * @property integer $order
 *
 * @property \item\models\item\Item $item
 * @property \item\models\media\Media $media
 */
class ItemHasMediaBase extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_has_media';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'media_id'], 'required'],
            [['item_id', 'media_id', 'order'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'media_id' => 'Media ID',
            'order' => 'Order',
        ];
    }

    public function beforeSave($insert)
    {
        while (ItemHasMedia::find()->where([
                'item_id' => $this->item_id,
                'order' => $this->order
            ])->andWhere('media_id != :mId')->addParams([':mId' => $this->media_id])->count() > 0) {
            $this->order++;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(\item\models\item\Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(\item\models\media\Media::className(), ['id' => 'media_id']);
    }
}
