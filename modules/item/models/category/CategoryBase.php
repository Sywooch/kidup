<?php

namespace item\models\category;

use item\models\categoryHasItemFacet\CategoryHasItemFacet;
use item\models\itemFacet\ItemFacet;
use Yii;

/**
 * This is the base-model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $itemCount
 *
 * @property \item\models\category\Category $parent
 * @property \item\models\category\Category[] $children
 * @property CategoryHasItemFacet[] $categoryHasItemFacets
 * @property ItemFacet[] $itemFacets
 */
class CategoryBase extends \app\components\models\BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [
                ['parent_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::className(),
                'targetAttribute' => ['parent_id' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' =>'ID',
            'name' =>'Name',
            'parent_id' =>'Parent ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(\item\models\category\Category::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(\item\models\category\Category::className(), ['parent_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\item\models\item\Item::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemCount()
    {
        return $this->hasMany(\item\models\item\Item::className(), ['category_id' => 'id'])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryHasItemFacets()
    {
        return $this->hasMany(CategoryHasItemFacet::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemFacets()
    {
        return $this->hasMany(ItemFacet::className(), ['id' => 'item_facet_id'])->viaTable(CategoryHasItemFacet::tableName(),
            ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNonSingularItemFacets()
    {
        return $this->hasMany(ItemFacet::className(), ['id' => 'item_facet_id'])->viaTable(CategoryHasItemFacet::tableName(),
            ['category_id' => 'id'])->where(['item_facet.is_singular' => 0]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSingularItemFacets()
    {
        return $this->hasMany(ItemFacet::className(), ['id' => 'item_facet_id'])->viaTable(CategoryHasItemFacet::tableName(),
            ['category_id' => 'id'])->where(['item_facet.is_singular' => 1]);
    }

}
