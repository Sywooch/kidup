<?php

namespace app\modules\item\models;

use app\components\Cache;
use app\models\base\Currency;
use app\modules\images\components\ImageHelper;
use app\modules\user\models\User;
use Carbon\Carbon;
use Location\Coordinate;
use Location\Distance\Vincenty;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This is the model class for table "item".
 */
class Item extends \app\models\base\Item
{
    const EVENT_UNFINISHED_REMINDER = 'unfinished_reminder';

    public $images;
    public $distance;

    public static function getConditions()
    {
        return [
            '' => '',
            '0' => \Yii::t('item', 'As good as new'),
            '1' => \Yii::t('item', 'Minor usage trails'),
            '2' => \Yii::t('item', 'Lightly damaged')
        ];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'description', 'price_week', 'min_renting_days', 'condition'], 'required'],
            [['price_day', 'price_week', 'price_month'], 'integer', 'min' => 0, 'max' => 999999],
            [['condition'], 'in', 'range' => [0, 1, 2]],
            [['description'], 'string', 'min' => 5],
            [['name'], 'string', 'max' => 50]
        ]);
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'price_day' => Yii::t('app', 'Price Day'),
            'price_week' => Yii::t('app', 'Price Week'),
            'price_month' => Yii::t('app', 'Price Month'),
            'owner_id' => Yii::t('app', 'Owner ID'),
            'condition' => Yii::t('app', 'Condition'),
            'currency_id' => Yii::t('app', 'Currency ID'),
            'is_available' => Yii::t('app', 'Is Available'),
            'created_at' => Yii::t('app', 'Created At'),
            'age_min' => Yii::t('app', 'Minimal age of use'),
            'age_max' => Yii::t('app', 'Maximal age of use'),
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['owner_id', 'is_available', 'min_renting_days'],
            'default' => [
                'name',
                'description',
                'price_week',
                'owner_id',
                'condition',
                'currency_id',
                'min_renting_days'
            ],
        ];
    }

    public function backup()
    {
        $item = Item::find()->where(['id' => $this->item_id])->asArray()->with([
            'owner.profile',
            'owner.locations'
        ])->one();
        $renter = User::find()->where(['id' => $this->renter_id])->asArray()->with(['profile', 'locations'])->one();
        $this->item_backup = json_encode(['item' => $item, 'renter' => $renter]);

        return $this->save();
    }

    public function beforeSave($insert)
    {
        if ($insert == true) {
            $this->created_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
            $this->currency_id = 1;
            $this->owner_id = Yii::$app->user->id;
        }
        $this->updated_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;

        return parent::beforeSave($insert);
    }

    public function beforeValidate()
    {
        $this->name = \yii\helpers\HtmlPurifier::process($this->name);
        $this->description = \yii\helpers\HtmlPurifier::process($this->description);
        return parent::beforeValidate();
    }

    public function getImageNames()
    {
        $function = function () {
            $ihms = ItemHasMedia::getDb()->cache(function () {
                return ItemHasMedia::find()->where(['item_id' => $this->id])->orderBy('order')->with('media')->all();
            });

            $imgs = [];
            foreach ($ihms as $ihm) {
                $imgs[] = $ihm->media->file_name;
            }
            return $imgs;
        };
        return Cache::data('item_names' . $this->id, $function, 60 * 60);
    }

    public function getImageName($order)
    {
        $ihms = $this->getImageNames();

        if (isset($ihms[$order])) {
            return $ihms[$order];
        }
        return false;
    }

    public function isOwner($userId = null)
    {
        if ($userId == null) {
            $userId = \Yii::$app->user->id;
        }
        return $this->owner_id == $userId;
    }

    /**
     * Returns an array of pricing details for a booking of a certain time range
     * @param int $timestampFrom
     * @param int $timestampTo
     * @param Currency $currency
     * @return array
     */
    public function getPriceForPeriod($timestampFrom, $timestampTo, Currency $currency)
    {
        if ($currency !== $this->currency) {
            // todo convert the currency
        }

        $days = floor(($timestampTo - $timestampFrom) / (60 * 60 * 24));
        $dailyPrices = [
            'day' => $this->price_day,
            'week' => $this->price_week / 7,
            'month' => $this->price_week / 30,
        ];
        if ($days <= 7) {
            $price = $dailyPrices['day'] > 0 ? $days * $dailyPrices['day'] : $days * $dailyPrices['week'];
        } elseif ($days > 7 && $days <= 31) {
            $price = $dailyPrices['week'] * $days;
        } else {
            $price = $dailyPrices['month'] > 0 ? $days * $dailyPrices['month'] : $days * $dailyPrices['week'];
        }

        $payinFee = \Yii::$app->params['payinServiceFeePercentage'] * $price;
        $payinFeeTax = $payinFee * 0.25; // static tax for now
        return [
            'price' => round($price),
            'fee' => round($payinFee + $payinFeeTax),
            'total' => round($price + $payinFee + $payinFeeTax),
            '_detailed' => [
                'price' => $price,
                'fee' => $payinFee,
                'feeTax' => $payinFeeTax
            ]
        ];
    }

    public function getCarouselImages(){
        return Cache::data('item_view-images-carousel' . $this->id, function () {
            $itemImages = $this->getImageNames();
            $images = [];
            foreach ($itemImages as $img) {
                $images[] = [
                    'src' => ImageHelper::url($img, ['q' => 90, 'w' => 400]),
                    'url' => ImageHelper::url($img, ['q' => 90, 'w' => 1600]),
                ];
            }
            return $images;
        }, 10 * 60);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }

    public function getCategoriesMain()
    {
        return $this->hasMany(\app\models\base\Category::className(), ['id' => 'category_id'])
            ->where(['category.type' => Category::TYPE_MAIN])
            ->indexBy('id')
            ->asArray()
            ->viaTable('item_has_category', ['item_id' => 'id']);
    }

    public function getCategoriesAge()
    {
        return $this->hasMany(\app\models\base\Category::className(), ['id' => 'category_id'])
            ->where(['category.type' => Category::TYPE_AGE])
            ->indexBy('id')
            ->asArray()
            ->viaTable('item_has_category', ['item_id' => 'id']);
    }

    public function getCategoriesSpecial()
    {
        return $this->hasMany(\app\models\base\Category::className(), ['id' => 'category_id'])
            ->where(['category.type' => Category::TYPE_SPECIAL])
            ->indexBy('id')
            ->asArray()
            ->viaTable('item_has_category', ['item_id' => 'id']);
    }


    /**
     * Returns the categories of this item of a certain type
     * @param $type
     * @return array
     */
    public function getCategoriesByType($type)
    {
        $cats = Category::find()->innerJoinWith('itemHasCategories')->
        where([
            'type' => $type,
            'item_has_category.item_id' => $this->getAttribute('id')
        ])->asArray()->all();
        $res = [];
        foreach ($cats as $cat) {
            $res[] = $cat['name'];
        }
        return $res;
    }

    /**
     * Get a list of recommended items.
     *
     * @param $item Item  the item to find recommended items for
     * @param $numItems int the maximum number of items to retrieve
     * @return array    a list with the recommended items
     */
    public function getRecommendedItems($item, $numItems = 3)
    {
        $similarities = ItemSimilarity::find()->where(['item_id_1' => $item->id])->limit($numItems)->orderBy('similarity DESC')->all();
        if (count($similarities) == 0) {
            (new ItemSimilarity())->compute($item);
        }
        $similarities = ItemSimilarity::find()->where(['item_id_1' => $item->id])->limit($numItems)->orderBy('similarity DESC')->all();
        $res = [];
        foreach ($similarities as $s) {
            $res[] = $s->similarItem;
        }

        return $res;
    }
}
