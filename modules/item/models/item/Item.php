<?php

namespace item\models\item;

use app\components\Cache;
use app\components\Exception;
use images\components\ImageHelper;
use item\models\itemHasMedia\ItemHasMedia;
use item\models\itemSimilarity\ItemSimilarity;
use item\models\media\Media;
use search\components\ItemSearchDb;
use user\models\currency\Currency;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class ItemException extends Exception
{
}
/**
 * This is the model class for table "item".
 */
class Item extends ItemBase
{
    const EVENT_UNFINISHED_REMINDER = 'unfinished_reminder';

    public $images;
    public $distance;

    /**
     * Get $numItems recommended items.
     *
     * @param $numItems int The number of recommended items to get.
     * @return Item[] The list of recommended items.
     */
    public static function getRecommended($numItems)
    {
        $items = self::find()->limit($numItems)->orderRandomly()->available()->all();
        if (count($items) < $numItems) {
            $items = ArrayHelper::merge($items,
                self::find()->limit($numItems - count($items))->orderRandomly()->available()->all());
        }
        return $items;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->is_available == 1) {
            (new ItemSearchDb())->sync([$this]);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function extraFields()
    {
        return ['owner', 'category', 'location', 'currency', 'media'];
    }

    public function isAvailable()
    {
        return $this->is_available == 1;
    }

    public function beforeSave($insert)
    {
        if (!$insert && !$this->hasModifyRights()) {
            return false;
        }
        if ($insert == true) {
            $this->currency_id = 1;
        }
        if (!$this->isAvailable()) {
            $this->is_available = 0;
        }

        return parent::beforeSave($insert);
    }

    public function beforeDelete()
    {
        if (!$this->hasModifyRights()) {
            throw new ItemException("No deletion rights");
        }
        if($this->isAvailable()){
            (new ItemSearchDb())->removeItem($this);
        }
        return parent::beforeDelete();
    }

    /**
     * Whether or not the current logged in user has the rights to modify this item.
     *
     * @return bool  True if modifications by the logged in user are allowed, false otherwise.
     */
    public function hasModifyRights($user = null)
    {
        return $this->isOwner($user) || \Yii::$app->user->identity->isAdmin();
    }

    public function beforeValidate()
    {
        $this->name = \yii\helpers\HtmlPurifier::process($this->name);
        $this->description = \yii\helpers\HtmlPurifier::process($this->description);
        if (!isset($this->owner_id) && $this->isNewRecord) {
            $this->owner_id = Yii::$app->user->id;
        }
        return parent::beforeValidate();
    }

    public function getImageNames()
    {
        $function = function () {
            $ihms = ItemHasMedia::find()->where(['item_id' => $this->id])->orderBy('order')->with('media')->all();
            return array_map(function($ihm){
                return  $ihm->media->file_name;
            }, $ihms);
        };
        return Cache::build('item-image-names')
            ->variations([$this->id])
            ->duration(60 * 60)->data($function);
    }

    public function getImageName($order)
    {
        $ihms = $this->getImageNames();

        if (isset($ihms[$order])) {
            return $ihms[$order];
        }
        return false;
    }

    public function isOwner($user = null)
    {
        if (!is_object($user)) {
            $userId = $user;
        } else {
            $userId = $user->id;
        }
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
    public function preloadMedia()
    {
        $preload = [];
        $allMedia = Media::find()->where(['item_has_media.item_id' => $this->id])
            ->innerJoinWith('itemHasMedia')
            ->orderBy('item_has_media.order')
            ->all();
        foreach ($allMedia as $media) {
            $preload[] = [
                'name' => ImageHelper::url($media->file_name),
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
        $days = floor(($timestampTo - $timestampFrom) / (60 * 60 * 24));
        $dailyPrices = [
            'day' => $this->price_day,
            'week' => $this->price_week / 7,
            'month' => $this->price_month / 30,
            'year' => $this->price_year / 356,
        ];

        // assume that only weekly price is set for sure
        $using = [];
        $isDiscounted = false;
        if ($days <= 7) {
            $price = $dailyPrices['day'] > 0 ? $days * $dailyPrices['day'] : $days * $dailyPrices['week'];
            $using = ['day', $days];
            if ($this->price_week < $price) {
                $price = $this->price_week;
                $using = ['week', 1];
                $isDiscounted = true;
            }
        } elseif ($days > 7 && $days <= 31) {
            $price = $dailyPrices['week'] * $days;
            $using = ['week', round($days / 7, 1)];
            if (is_int($this->price_month)) {
                if ($this->price_month < $price) {
                    $price = $this->price_month;
                    $using = ['month', 1];
                    $isDiscounted = true;
                }
            }
        } elseif ($days > 31 && $days <= 356) {
            $price = $dailyPrices['month'] > 0 ? $days * $dailyPrices['month'] : $days * $dailyPrices['week'];
            $using = ['month', round($days / 30, 1)];
            if (is_int($this->price_year)) {
                if ($this->price_year < $price) {
                    $price = $this->price_year;
                    $using = ['year', 1];
                    $isDiscounted = true;
                }
            }
        } else {
            $price = $dailyPrices['year'] > 0 ? $days * $dailyPrices['year'] :
                ($dailyPrices['month'] > 0 ? $days * $dailyPrices['month'] : $days * $dailyPrices['week']);
            $using = ['year', round($days / 365, 1)];
        }

        $using = ['period' => $using[0], 'count' => $using[1]];

        if ($using['period'] == 'day') {
            $using['period_text'] = \Yii::t('item.pricing_table.day_period', '{n, plural, =1{1 day} other{# days}}',
                ['n' => $using['count']]);
            $using['period_price'] = $this->price_day;
        } elseif ($using['period'] == 'week') {
            $using['period_text'] = \Yii::t('item.pricing_table.week_period', '{n, plural, =1{1 week} other{# weeks}}',
                ['n' => $using['count']]);
            $using['period_price'] = $this->price_week;
        } elseif ($using['period'] == 'month') {
            $using['period_text'] = \Yii::t('item.pricing_table.month_period',
                '{n, plural, =1{1 month} other{# months}}', ['n' => $using['count']]);
            $using['period_price'] = $this->price_month;
        } else {
            $using['period_text'] = \Yii::t('item.pricing_table.month_year',
                '{n, plural, =1{1 year} other{# years}}', ['n' => $using['count']]);
            $using['period_price'] = $this->price_year;
        }

        $payinFee = \Yii::$app->params['payinServiceFeePercentage'] * $price;
        $payinFeeTax = $payinFee * 0.25; // static tax for now
        return [
            'price' => round($price),
            'fee' => round($payinFee + $payinFeeTax),
            'total' => round($price + $payinFee + $payinFeeTax),
            'periodInfo' => $using,
            'isDiscounted' => $isDiscounted,
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
    public function getTableData($from, $to, Currency $currency)
    {
        $prices = $this->getPriceForPeriod($from, $to, $currency);
        $days = floor(($to - $from) / (60 * 60 * 24));

        return [
            'price' => [
                $prices['periodInfo']['period_text'] . ' x ' . $currency->forex_name . ' ' . $prices['periodInfo']['period_price'],
                $currency->abbr . ' ' . $prices['price'],
                $prices['isDiscounted']
            ],
            'fee' => [\Yii::t('item.pricing_table.service_fee', 'Service fee'), $currency->abbr . ' ' . $prices['fee']],
            'total' => [\Yii::t('item.pricing_table.total', 'Total'), $currency->abbr . ' ' . $prices['total']]
        ];
    }

    public function getCarouselImages()
    {
        $func = function () {
            $itemImages = $this->getImageNames();

            $images = [];
            $count = count($itemImages);
            foreach ($itemImages as $i => $img) {
                if ($count == 1 || ($i == 0 && $count > 2)) {
                    $w = 650;
                    $h = 300;
                } else {
                    $w = 250;
                    $h = 200;
                }

                $images[] = [
                    'src' => ImageHelper::url($img, ['q' => 90, 'w' => $w, 'h' => $h, 'fit' => 'crop']),
                    'url' => ImageHelper::url($img, ['q' => 90]),
                ];
            }
            return $images;
        };

        return Cache::build('item-view-images-carousel')
            ->variations([$this->id])
            ->duration(10 * 60)
            ->data($func);
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
            if (count($res) >= $numItems) {
                return $res;
            }
            if ($s->similarItem->isAvailable()) {
                $res[] = $s->similarItem;
            }
        }

        return $res;
    }

    /**
     * Returns the daily price for this item
     * @return double
     */
    public function getDailyPrice()
    {
        return isset($this->price_day) ? $this->price_day : $this->price_week / 3;
    }

    /**
     * Returns the daily price for this item
     * @return double
     */
    public function getWeeklyPrice()
    {
        return $this->price_week;
    }

    /**
     * Returns the daily price for this item
     * @return double
     */
    public function getMonthlyPrice()
    {
        return isset($this->price_month) ? $this->price_month : $this->price_week * 2.5;
    }

    /**
     * Returns the daily price for this item
     * @return double
     */
    public function getYearlyPrice()
    {
        if (isset($this->price_year)) {
            return $this->price_year;
        }
        if (isset($this->price_month)) {
            return $this->price_month * 3.5;
        }
        return $this->price_week * 2.5 * 3.5;
    }
}
