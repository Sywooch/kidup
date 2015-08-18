<?php

namespace app\modules\item\models;

use app\components\Cache;
use app\models\base\ItemHasCategory;
use app\modules\user\models\User;
use Carbon\Carbon;
use Location\Coordinate;
use Location\Distance\Vincenty;
use Yii;
use yii\helpers\Html;

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
            [['description'], 'string', 'min' => 5]
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
            'create' => ['owner_id', 'name', 'is_available', 'condition', 'location_id', 'min_renting_days'],
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

    public function isPublishable()
    {
        $errors = [];
        if (!$this->validate()) {
            foreach ($this->getErrors() as $errorC) {
                foreach ($errorC as $error) {
                    $errors[] = $error;
                }
            }
        }
        $location = Location::find()->where(['id' => $this->location_id])->one();
        if (is_null($location) || !$location->isValid()) {
            $errors[] = \Yii::t('item', 'Please set your location in your {0}.', [
                Html::a(\Yii::t('item', 'location settings'), '@web/user/settings/location', ['target' => '_blank'])
            ]);
        }
        if (strlen($this->owner->profile->first_name) <= 1 || strlen($this->owner->profile->last_name) <= 1) {
            $errors[] = \Yii::t('item', 'Please complete your {0}.', [
                Html::a(\Yii::t('item', 'profile'), '@web/user/settings/profile', ['target' => '_blank'])
            ]);
        }
        /**
         * @var \app\modules\user\models\User $u ;
         */
        $u = User::findone($this->owner_id);
//        if(!$u->hasValidPayoutMethod()){
//            $errors[] = \Yii::t('item', 'Please complete your {0}.', [
//                Html::a(\Yii::t('item', 'preferred payout method'), '@web/user/settings/payout-preference', ['target' => '_blank'])
//            ]);
//        }
        if (count($this->media) == 0) {
            $errors[] = \Yii::t('item', 'An item needs atleast one image.');
        }

        return count($errors) == 0 ? true : $errors;
    }

    public function getUserDistance()
    {
        if (!\Yii::$app->session->has('location_cache')) {
            return false;
        }
        $location = \Yii::$app->session->get('location_cache');
        $coordinate1 = new Coordinate($location['longitude'], $location['latitude']);
        $coordinate2 = new Coordinate($this->location->longitude, $this->location->latitude);

        $calculator = new Vincenty();

        echo $calculator->getDistance($coordinate1, $coordinate2);
    }

    public function getAdPrice()
    {
        return [
            'price' => $this->price_week,
            'period' => 'week'
        ];
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
     * @param $item_id int  the item to find recommended items for
     * @param $numItems int the maximum number of items to retrieve
     * @return array    a list with the recommended items
     */
    public function getRecommendedItems($item_id, $numItems)
    {
        $item1 = Item::find()->where(['id' => $item_id])->one();

        // now fetch all other items
        $items = Item::find()
            ->where('id != ' . $item_id . ' and is_available = 1')
            ->all();

        // create a ranking and calculate the scores between the current item and all other items
        $ranking = [];
        foreach ($items as $item2) {
            $ranking[$item2->id] = Item::getSimilarityScore($item1, $item2);
        }

        // sort the ranking and find the results
        arsort($ranking);
        $results = [];
        foreach ($ranking as $other_item_id => $score) {
            $results[] = Item::find()->where(['id' => $other_item_id])->one();
            if (count($results) >= $numItems) {
                break;
            }
        }

        return $results;
    }

    /**
     * Calculate a similarity score between two items
     *
     * @param int $item1 first item
     * @param $item2 second item
     * @return double number in [0, 1] where 0 means maximal dissimilarity and 1 means maximal similarity
     */
    public static function getSimilarityScore($item1, $item2)
    {
        // calculate the geographical distance
        $loc1 = Location::find(['id' => $item1->location_id])->one();
        $loc2 = Location::find(['id' => $item2->location_id])->one();
        $geoDistance = Item::calculateGeoDistance($loc1->latitude, $loc1->longitude, $loc2->latitude, $loc2->longitude);

        // calculate the categorical distance
        $cats1 = ItemHasCategory::find()->where(['item_id' => $item1->id])->all();
        $numSameCategories = 0;
        foreach ($cats1 as $cat1) {
            $cats2 = ItemHasCategory::find()
                ->where(['item_id' => $item2->id])
                ->andWhere(['category_id' => $cat1->category_id])
                ->one();
            $numSameCategories += count($cats2);
        }

        // $geoScore \in [0, 1] where 1 maximal similarity
        $geoScore = 1 / ($geoDistance + 1);

        // $catScore \in [0, 1] where 1 maximal similarity
        $catScore = 1 - 1 / ($numSameCategories + 1);

        return 0.7 * $geoScore + 0.3 * $catScore;
    }

    /**
     * Calculate the geographical distance between two pairs of longitude and latitude coordinates.
     *
     * @param $lat1 first latitude coordinate
     * @param $lon1 first longitude coordinate
     * @param $lat2 second latitude coordinate
     * @param $lon2 second longitude coordinate
     * @return float the distance (in kilometers)
     */
    public static function calculateGeoDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return $miles * 1.609344;
    }

}
