<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "item_has_feature_singular".
 *
 * @property integer $item_id
 * @property integer $feature_id
 *
 * @property \app\models\Feature $feature
 * @property \app\models\Item $item
 */
class ItemHasFeatureSingular extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_has_feature_singular';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['feature_id'], 'required'],
            [['feature_id'], 'integer'],
            [['feature_id'], 'exist', 'skipOnError' => true, 'targetClass' => Feature::className(), 'targetAttribute' => ['feature_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']]
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeature()
    {
        return $this->hasOne(\app\models\Feature::className(), ['id' => 'feature_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(\app\models\Item::className(), ['id' => 'item_id']);
    }




}
