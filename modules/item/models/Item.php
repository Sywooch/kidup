<?php

namespace app\modules\item\models;

use app\modules\user\models\User;
use Carbon\Carbon;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Location\Coordinate;
use Location\Distance\Vincenty;
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
            [['name', 'description', 'price_week', 'min_renting_days', 'condition'], 'required']
        ]);
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

    public function getImageUrls()
    {
        $ihms = ItemHasMedia::find()->where(['item_id' => $this->id])->orderBy('order')->all();
        usort($ihms, function($a, $b){
            return ($a->order < $b->order) ? -1 : 1;
        });

        $this->images = [];
        foreach ($ihms as $ihm) {
            $this->images[] = $ihm->media->file_name;
        }
        return $this->images;
    }

    public function isOwner($userId = null){
        if($userId == null){
            $userId = \Yii::$app->user->id;
        }
        return $this->owner_id == $userId;
    }

    public function isPublishable(){
        $errors = [];
        if(!$this->validate()){
            foreach ($this->getErrors() as $errorC) {
                foreach ($errorC as $error) {
                    $errors[] = $error;
                }
            }
        }
        $location = Location::findOne($this->location_id);
        if(is_null($location) || !$location->isValid()){
            $errors[] = \Yii::t('item', 'Please set your location in your {0}.',[
                Html::a(\Yii::t('item', 'location settings'), '@web/user/settings/location', ['target' => '_blank'])
            ]);
        }
        if(strlen($this->owner->profile->first_name) <= 1 || strlen($this->owner->profile->last_name) <= 1){
            $errors[] = \Yii::t('item', 'Please complete your {0}.', [
                Html::a(\Yii::t('item', 'profile'), '@web/user/settings/profile', ['target' => '_blank'])
            ]);
        }
        /**
         * @var \app\modules\user\models\User $u;
         */
        $u = User::findone($this->owner_id);
//        if(!$u->hasValidPayoutMethod()){
//            $errors[] = \Yii::t('item', 'Please complete your {0}.', [
//                Html::a(\Yii::t('item', 'preferred payout method'), '@web/user/settings/payout-preference', ['target' => '_blank'])
//            ]);
//        }
        if(count($this->media) == 0){
            $errors[] = \Yii::t('item', 'An item needs atleast one image.');
        }

        return count($errors) == 0 ? true : $errors;
    }

    public function getUserDistance(){
        if(!\Yii::$app->session->has('location_cache')){
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
}
