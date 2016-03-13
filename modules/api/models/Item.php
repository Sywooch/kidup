<?php

namespace api\models;

use images\components\ImageHelper;
use item\models\itemHasMedia\ItemHasMedia;
use item\models\wishListItem\WishListItem;
use yii\helpers\Json;

/**
 * This is the model class for table "item".
 */
class Item extends \item\models\item\Item
{

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            'default' => ['owner_id']
        ]);
    }

    public function beforeValidate()
    {
        if (!isset($this->category_id)) {
            $this->category_id = 44;
        }
        if ($this->isNewRecord) {
            $this->owner_id = \Yii::$app->user->id;
        }
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['updated_at']);

        $fields['item_facets'] = function ($model) {
            $res = [];
            $itemFacets = $this->itemHasItemFacets;
            foreach ($itemFacets as $itemFacet) {
                $itemFacet->itemFacet->name = $itemFacet->itemFacet->getTranslatedName();
                $itemFacet->itemFacet->description = $itemFacet->itemFacet->getTranslatedDescription();
                $f = Json::decode(Json::encode($itemFacet->itemFacet));
                if (count($itemFacet->itemFacetValue) > 0) {
                    $f = array_merge($f, [
                        'value' => $itemFacet->itemFacetValue->getTranslatedName(),
                        'value_id' => $itemFacet->item_facet_value_id
                    ]);
                }
                $res[] = $f;
            }
            return $res;
        };

        $fields['price_day'] = function () {
            return round($this->getDailyPrice());
        };
        $fields['price_week'] = function () {
            return round($this->getWeeklyPrice());
        };
        $fields['price_month'] = function () {
            return round($this->getMonthlyPrice());
        };
        $fields['price_year'] = function () {
            return round($this->getYearlyPrice());
        };
        $mainImage = function ($model) {
            $media = ItemHasMedia::find()->where([
                'item_id' => $model->id
            ])->orderBy('order')->one();
            if ($media == null) {
                return false;
            }
            return rtrim(ImageHelper::url($media->media->file_name), '?');
        };
        $fields['image_base_url'] = $mainImage;
        $fields['image'] = $mainImage;
        $fields['user_has_item_on_wishlist'] = function () {
            if (!\Yii::$app->user->isGuest) {
                return WishListItem::find()->where(['user_id' => \Yii::$app->user->id])->count() > 0;
            }
            return false;
        };
        return $fields;
    }

    public function extraFields()
    {
        return ['owner', 'category', 'location', 'currency', 'media'];
    }

    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['id' => 'media_id'])
            ->viaTable('item_has_media', ['item_id' => 'id']);
    }
}
