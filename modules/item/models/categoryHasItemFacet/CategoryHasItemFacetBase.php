<?php

namespace item\models\categoryHasItemFacet;

use item\models\category\Category;
use item\models\itemFacet\ItemFacet;
use Yii;

/**
 * This is the base-model class for table "category_has_item_facet".
 *
 * @property integer $category_id
 * @property integer $item_facet_id
 *
 * @property ItemFacet $item_facet
 * @property Category $category
 */
class CategoryHasItemFacetBase extends \app\components\models\BaseActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_has_item_facet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_facet_id'], 'required'],
            [['item_facet_id'], 'integer'],
            [
                ['item_facet_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ItemFacet::className(),
                'targetAttribute' => ['item_facet_id' => 'id']
            ],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::className(),
                'targetAttribute' => ['category_id' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'item_facet_id' => 'ItemFacet ID',
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
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }


}
