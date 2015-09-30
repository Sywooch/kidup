<?php

namespace item\models\base;

use Yii;
use item\models\Item;

/**
 * This is the base-model class for table "item_similarity".
 *
 * @property integer $item_id_1
 * @property integer $item_id_2
 * @property double $similarity
 * @property double $similarity_location
 * @property double $similarity_categories
 * @property double $similarity_price
 *
 * @property Item $itemId2
 * @property Item $itemId1
 */
class ItemSimilarity extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_similarity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id_1', 'item_id_2'], 'required'],
            [['item_id_1', 'item_id_2'], 'integer'],
            [['similarity', 'similarity_location', 'similarity_categories', 'similarity_price'], 'number'],
            [
                ['item_id_2'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Item::className(),
                'targetAttribute' => ['item_id_2' => 'id']
            ],
            [
                ['item_id_1'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Item::className(),
                'targetAttribute' => ['item_id_1' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id_1' => 'Item Id 1',
            'item_id_2' => 'Item Id 2',
            'similarity' => 'Similarity',
            'similarity_location' => 'Similarity Location',
            'similarity_categories' => 'Similarity Categories',
            'similarity_price' => 'Similarity Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemId2()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id_2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemId1()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id_1']);
    }


}
