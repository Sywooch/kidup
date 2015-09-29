<?php

namespace item\models\base;

use Yii;

/**
 * This is the base-model class for table "item_has_feature".
 *
 * @property integer $item_id
 * @property integer $feature_id
 * @property integer $feature_value_id
 *
 * @property FeatureValue $featureValue
 * @property Feature $feature
 * @property Item $item
 */
class ItemHasFeature extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_has_feature';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['feature_id', 'feature_value_id'], 'required'],
            [['feature_id', 'feature_value_id'], 'integer'],
            [
                ['feature_value_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => FeatureValue::className(),
                'targetAttribute' => ['feature_value_id' => 'id']
            ],
            [
                ['feature_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Feature::className(),
                'targetAttribute' => ['feature_id' => 'id']
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
            'feature_id' => 'Feature ID',
            'feature_value_id' => 'Feature Values ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeatureValue()
    {
        return $this->hasOne(FeatureValue::className(), ['id' => 'feature_value_id']);
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
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }


}
