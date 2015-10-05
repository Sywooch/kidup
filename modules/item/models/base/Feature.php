<?php

namespace item\models\base;

use Yii;

/**
 * This is the base-model class for table "feature".
 *
 * @property integer $id
 * @property integer $is_singular
 * @property string $name
 * @property string $description
 * @property integer $is_required
 *
 * @property CategoryHasFeature[] $categoryHasFeatures
 * @property Category[] $categories
 * @property FeatureValue[] $featureValues
 * @property ItemHasFeature[] $itemHasFeatures
 * @property Item[] $items
 * @property ItemHasFeatureSingular[] $itemHasFeatureSingulars
 * @property Item[] $items0
 */
class Feature extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feature';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_singular', 'name'], 'required'],
            [['is_singular', 'is_required'], 'integer'],
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
            'is_singular' => 'Is Singular',
            'name' => 'Name',
            'description' => 'Description',
            'is_required' => 'Is Required',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryHasFeatures()
    {
        return $this->hasMany(CategoryHasFeature::className(), ['feature_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('category_has_feature', ['feature_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeatureValues()
    {
        return $this->hasMany(FeatureValue::className(), ['feature_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemHasFeatures()
    {
        return $this->hasMany(ItemHasFeature::className(), ['feature_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['id' => 'item_id'])->viaTable('item_has_feature', ['feature_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemHasFeatureSingulars()
    {
        return $this->hasMany(ItemHasFeatureSingular::className(), ['feature_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems0()
    {
        return $this->hasMany(Item::className(), ['id' => 'item_id'])->viaTable('item_has_feature_singular', ['feature_id' => 'id']);
    }

    public function getTranslatedName(){
        $lower = str_replace(" ", "_", strtolower($this->name));
        return \Yii::$app->getI18n()->translate('item.feature.' . $lower . '_name', $this->name, [], \Yii::$app->language);
    }

    public function getTranslatedDescription(){
        $lower = str_replace(" ", "_", strtolower($this->name));
        return \Yii::$app->getI18n()->translate('item.feature.' . $lower . '_description', $this->name, [], \Yii::$app->language);
    }
}
