<?php

namespace item\models\base;

use Yii;

/**
 * This is the base-model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $itemCount
 *
 * @property \item\models\Category $parent
 * @property \item\models\Category[] $children
 * @property CategoryHasFeature[] $categoryHasFeatures
 * @property Feature[] $features
 * @property Feature[] $singularFeatures
 * @property Feature[] $nonSingularFeatures
 */
class Category extends \yii\db\ActiveRecord
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
        return $this->hasOne(\item\models\Category::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(\item\models\Category::className(), ['parent_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\item\models\Item::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemCount()
    {
        return $this->hasMany(\item\models\Item::className(), ['category_id' => 'id'])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryHasFeatures()
    {
        return $this->hasMany(CategoryHasFeature::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeatures()
    {
        return $this->hasMany(Feature::className(), ['id' => 'feature_id'])->viaTable('category_has_feature',
            ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNonSingularFeatures()
    {
        return $this->hasMany(Feature::className(), ['id' => 'feature_id'])->viaTable('category_has_feature',
            ['category_id' => 'id'])->where(['feature.is_singular' => 0]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSingularFeatures()
    {
        return $this->hasMany(Feature::className(), ['id' => 'feature_id'])->viaTable('category_has_feature',
            ['category_id' => 'id'])->where(['feature.is_singular' => 1]);
    }

}
