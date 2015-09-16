<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "item_search".
 *
 * @property string $component_type
 * @property integer $component_id
 * @property string $text
 * @property string $language_id
 */
class ItemSearch extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_search';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['component_type', 'component_id', 'text', 'language_id'], 'required'],
            [['component_id'], 'integer'],
            [['component_type'], 'string', 'max' => 10],
            [['text'], 'string', 'max' => 45],
            [['language_id'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'component_type' => 'Component Type',
            'component_id' => 'Component ID',
            'text' => 'Text',
            'language_id' => 'Language ID',
        ];
    }




}
