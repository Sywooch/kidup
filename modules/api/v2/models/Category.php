<?php

namespace api\v2\models;

use item\models\categoryHasItemFacet\CategoryHasItemFacet;

class Category extends \item\models\category\Category
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['name'] = function($model){
            /**
             * @var Category $model
             */
            return $model->getTranslatedName();
        };

        return $fields;
    }

    public function extraFields()
    {
        return ['parent', 'itemFacets', 'children'];
    }

    public function getParent(){
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    public function getChildren(){
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    public function getItemFacets()
    {
        return $this->hasMany(ItemFacet::className(), ['id' => 'item_facet_id'])->viaTable(CategoryHasItemFacet::tableName(),
            ['category_id' => 'id']);
    }

    public function getNonSingularItemFacets()
    {
        return $this->hasMany(ItemFacet::className(), ['id' => 'item_facet_id'])->viaTable(CategoryHasItemFacet::tableName(),
            ['category_id' => 'id'])->where(['item_facet.is_singular' => 0]);
    }

    public function getSingularItemFacets()
    {
        return $this->hasMany(ItemFacet::className(), ['id' => 'item_facet_id'])->viaTable(CategoryHasItemFacet::tableName(),
            ['category_id' => 'id'])->where(['item_facet.is_singular' => 1]);
    }
}
