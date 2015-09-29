<?php

namespace item\models;

use app\components\Cache;
use app\models\base\Currency;
use \images\components\ImageHelper;
use \user\models\User;
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

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'description', 'price_week', 'min_renting_days'], 'required'],
            [['price_day', 'price_week', 'price_month'], 'integer', 'min' => 0, 'max' => 999999],
            [['description'], 'string', 'min' => 5],
            [['name'], 'string', 'max' => 50]
        ]);
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('item.attribute.id', 'ID'),
            'name' => Yii::t('item.attribute.name', 'Name'),
            'description' => Yii::t('item.attribute.description', 'Description'),
            'price_day' => Yii::t('item.attribute.price_day', 'Price Day'),
            'price_week' => Yii::t('item.attribute.price_week', 'Price Week'),
            'price_month' => Yii::t('item.attribute.price_month', 'Price Month'),
            'owner_id' => Yii::t('item.attribute.owner_id', 'Owner'),
            'currency_id' => Yii::t('item.attribute.currency_id', 'Currency'),
            'is_available' => Yii::t('item.attribute.is_available', 'Available to book'),
            'created_at' => Yii::t('item.attribute.created_at', 'Created At'),
            'category_id' => Yii::t('item.attribute.category_id', 'Category')
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['owner_id', 'is_available', 'min_renting_days', 'category_id'],
            'default' => [
                'name',
                'description',
                'price_week',
                'owner_id',
                'currency_id',
                'min_renting_days'
            ],
            'location' => ['location_id']
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
     * Prepares media for the image gallery plugin (on item page for example)
     * Used for item creation.
     *
     * @return string json of results
     */
    public function preloadMedia(){
        $preload = [];
        $allMedia = Media::find()->where(['item_has_media.item_id' => $this->id])
            ->innerJoinWith('itemHasMedia')
            ->orderBy('item_has_media.order')
            ->all();
        foreach ($allMedia as $media) {
            $preload[] = [
                'name' => ImageHelper::url($media->file_name, ['q' => 90, 'w' => 120, 'h' => 120, 'fit' => 'crop']),
                'size' => 10,
            ];
        }
        return Json::encode($preload);
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

    /**
     * Returns array with data for item price table (used in booking widget, booking confirmation page etc)
     * @param int $from
     * @param int $to
     * @param Currency $currency
     * @return bool
     */
    public function getTableData($from, $to, Currency $currency){
        $prices = $this->getPriceForPeriod($from, $to, $currency);
        $days = floor(($to - $from) / (60 * 60 * 24));
        if ($days <= 7) {
            $period = \Yii::t('item.pricing_table.day_period', '{n, plural, =1{1 day} other{# days}}', ['n' => $days]);
            $periodPrice = $this->price_day;
        } elseif ($days > 7 && $days <= 31) {
            $period = \Yii::t('item.pricing_table.week_period', '{n, plural, =1{1 week} other{# weeks}}', ['n' => round($days / 7)]);
            $periodPrice = $this->price_week;
        } else {
            $period = \Yii::t('item.pricing_table.month_period', '{n, plural, =1{1 month} other{# months}}', ['n' => round($days / 30)]);
            $periodPrice = $this->price_month;
        }
        return [
            'price' => [
                $period . ' x ' . $currency->forex_name . ' ' . $periodPrice,
                $currency->abbr . ' ' . $prices['price']
            ],
            'fee' => [\Yii::t('item.pricing_table.service_fee', 'Service fee'), $currency->abbr . ' ' . $prices['fee']],
            'total' => [\Yii::t('item.pricing_table.total', 'Total'), $currency->abbr . ' ' . $prices['total']]
        ];
    }

    public function getCarouselImages(){
        return Cache::data('item_view-images-carousel' . $this->id, function () {
            $itemImages = $this->getImageNames();

            $images = [];
            $count = count($itemImages);
            foreach ($itemImages as $i => $img) {
                if(count($itemImages) == 1){
                    $w = 650;
                    $h = 300;
                }else{
                    $w = 250;
                    $h = 200;
                }

                $images[] = [
                    'src' => ImageHelper::url($img, ['q' => 90, 'w' => $w, 'h' => $h, 'fit' => 'crop']),
                    'url' => ImageHelper::url($img, ['q' => 90]),
                ];
            }
            return $images;
        }, 10 * 60);
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
        $similarities = ItemSimilarity::find()->where(['item_id_1' => $item->id])->orderBy('similarity DESC')->all();
        if (count($similarities) == 0) {
            (new ItemSimilarity())->compute($item);
            $similarities = ItemSimilarity::find()->where(['item_id_1' => $item->id])->orderBy('similarity DESC')->all();
        }
        $res = [];
        foreach ($similarities as $s) {
            if(count($res) >= $numItems) return $res;
            if($s->similarItem->is_available == 1){
                $res[] = $s->similarItem;
            }
        }

        return $res;
    }
}
