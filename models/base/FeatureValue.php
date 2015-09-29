<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "feature_value".
 *
 * @property integer $id
 * @property integer $feature_id
 * @property string $name
 *
 * @property Feature $feature
 * @property ItemHasFeature[] $itemHasFeatures
 */
class FeatureValue extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feature_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['feature_id', 'name'], 'required'],
            [['feature_id'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [
                ['feature_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Feature::className(),
                'targetAttribute' => ['feature_id' => 'id']
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
            'feature_id' => 'Feature ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeature()
    {
        return $this->hasOne(Feature::className(), ['id' => 'feature_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemHasFeatures()
    {
        return $this->hasMany(ItemHasFeature::className(), ['feature_values_id' => 'id']);
    }

    public function getTranslatedName()
    {
        $lower = str_replace(" ", '_', strtolower($this->feature->name));
        $val = str_replace(" ", '_', strtolower($this->name));
        return \Yii::$app->getI18n()->translate('item.feature.' . $lower . '_value_' . $val, $lower, [],
            \Yii::$app->language);
    }
}
