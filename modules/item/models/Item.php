<?php

namespace app\modules\item\models;

use app\components\Cache;
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
     * @param $item Item  the item to find recommended items for
     * @param $numItems int the maximum number of items to retrieve
     * @return array    a list with the recommended items
     */
    public function getRecommendedItems($item, $numItems = 3)
    {
        $similarities = ItemSimilarity::find()->where(['item_id_1' => $item->id])->limit($numItems)->orderBy('similarity DESC')->all();
        if(count($similarities) == 0){
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
