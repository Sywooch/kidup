<?php

namespace item\models\itemFacetValue;

use admin\models\I18nMessage;
use item\models\itemFacet\ItemFacet;
use item\models\itemHasItemFacet\ItemHasItemFacet;
use Yii;

/**
 * This is the base-model class for table "item_facet_value".
 *
 * @property integer $id
 * @property integer $item_facet_id
 * @property string $name
 *
 * @property ItemFacet $itemFacet
 * @property ItemHasItemFacet[] $itemHasItemFacets
 */
class ItemFacetValueBase extends \app\components\models\BaseActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_facet_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_facet_id', 'name'], 'required'],
            [['item_facet_id'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [
                ['item_facet_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ItemFacet::className(),
                'targetAttribute' => ['item_facet_id' => 'id']
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
            'item_facet_id' => 'ItemFacet ID',
            'name' => 'Name',
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
    public function getItemHasItemFacets()
    {
        return $this->hasMany(ItemHasItemFacet::className(), ['item_facet_values_id' => 'id']);
    }

    public function getTranslatedName($lang = false)
    {
        $lower = str_replace(" ", '_', strtolower($this->itemFacet->name));
        $val = str_replace(" ", '_', strtolower($this->name));
        $index_name = 'item.feature.' . $lower . '_value_' . $val;
        return I18nMessage::findCustomMessage($index_name, $this->name, $lang);
    }
}
