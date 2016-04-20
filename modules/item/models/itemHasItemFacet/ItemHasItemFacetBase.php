<?php

namespace item\models\itemHasItemFacet;

use item\models\item\Item;
use item\models\itemFacet\ItemFacet;
use item\models\itemFacetValue\ItemFacetValue;
use Yii;

/**
 * This is the base-model class for table "item_has_item_facet".
 *
 * @property integer $item_id
 * @property integer $item_facet_id
 * @property integer $item_facet_value_id
 *
 * @property ItemFacetValue $itemFacetValue
 * @property ItemFacet $itemFacet
 * @property Item $item
 */
class ItemHasItemFacetBase extends \app\components\models\BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_has_item_facet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_facet_id'], 'required'],
            [['item_facet_id', 'item_facet_value_id'], 'integer'],
            [
                ['item_facet_value_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ItemFacetValue::className(),
                'targetAttribute' => ['item_facet_value_id' => 'id']
            ],
            [
                ['item_facet_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ItemFacet::className(),
                'targetAttribute' => ['item_facet_id' => 'id']
            ],
            [
                ['item_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Item::className(),
                'targetAttribute' => ['item_id' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'item_facet_id' => 'ItemFacet ID',
            'item_facet_value_id' => 'ItemFacet Values ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemFacetValue()
    {
        return $this->hasOne(ItemFacetValue::className(), ['id' => 'item_facet_value_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemFacet()
    {
        return $this->hasOne(ItemFacet::className(), ['id' => 'item_facet_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
