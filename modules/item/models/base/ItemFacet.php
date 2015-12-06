<?php

namespace item\models\base;

use Yii;

/**
 * This is the base-model class for table "item_facet".
 *
 * @property integer $id
 * @property integer $allow_multiple
 * @property string $name
 * @property string $description
 * @property integer $is_required
 *
 * @property CategoryHasItemFacet[] $categoryHasItemFacets
 * @property Category[] $categories
 * @property ItemFacetValue[] $itemFacetValues
 * @property ItemHasItemFacet[] $itemHasItemFacets
 * @property Item[] $items
 * @property Item[] $items0
 */
class ItemFacet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_facet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['allow_multiple', 'name'], 'required'],
            [['is_required'], 'integer'],
            [['allow_multiple'], 'boolean'],
            [['name'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'allow_multiple' => 'Allow Multiple Values',
            'name' => 'Name',
            'description' => 'Description',
            'is_required' => 'Is Required',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryHasItemFacets()
    {
        return $this->hasMany(CategoryHasItemFacet::className(), ['item_facet_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('category_has_item_facet',
            ['item_facet_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemFacetValues()
    {
        return $this->hasMany(ItemFacetValue::className(), ['item_facet_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemHasItemFacets()
    {
        return $this->hasMany(ItemHasItemFacet::className(), ['item_facet_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['id' => 'item_id'])->viaTable('item_has_item_facet',
            ['item_facet_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems0()
    {
        return $this->hasMany(Item::className(), ['id' => 'item_id'])->viaTable('item_has_item_facet_singular',
            ['item_facet_id' => 'id']);
    }

    public function getTranslatedName()
    {
        $lower = str_replace(" ", "_", strtolower($this->name));
        return \Yii::$app->getI18n()->translate('item.item_facet.' . $lower . '_name', $this->name, [],
            \Yii::$app->language);
    }

    public function getTranslatedDescription()
    {
        $lower = str_replace(" ", "_", strtolower($this->name));
        return \Yii::$app->getI18n()->translate('item.item_facet.' . $lower . '_description', $this->name, [],
            \Yii::$app->language);
    }
}
