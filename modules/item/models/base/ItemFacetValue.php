<?php

namespace item\models\base;

use Yii;

/**
 * This is the base-model class for table "item_facet_value".
 *
 * @property integer $id
 * @property integer $item_facet_id
 * @property string $name
 *
 * @property ItemFacet $item_facet
 * @property ItemHasItemFacet[] $itemHasItemFacets
 */
class ItemFacetValue extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_facet_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_facet_id', 'name'], 'required'],
            [['item_facet_id'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [
                ['item_facet_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ItemFacet::className(),
                'targetAttribute' => ['item_facet_id' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_facet_id' => 'ItemFacet ID',
            'name' => 'Name',
        ];
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
    public function getItemHasItemFacets()
    {
        return $this->hasMany(ItemHasItemFacet::className(), ['item_facet_values_id' => 'id']);
    }

    public function getTranslatedName()
    {
        $lower = str_replace(" ", '_', strtolower($this->itemFacet->name));
        $val = str_replace(" ", '_', strtolower($this->name));
        return \Yii::$app->getI18n()->translate('item.item_facet.' . $lower . '_value_' . $val, $this->name, [],
            \Yii::$app->language);
    }
}
