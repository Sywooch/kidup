<?php

namespace app\models\base;

use app\modules\search\models\ItemSearch;
use Yii;

/**
 * This is the base-model class for table "item".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $price_day
 * @property integer $price_week
 * @property integer $price_month
 * @property integer $owner_id
 * @property integer $condition
 * @property integer $currency_id
 * @property integer $is_available
 * @property integer $location_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $min_renting_days
 * @property integer $category_id
 *
 * @property Booking[] $bookings
 * @property Location $location
 * @property Currency $currency
 * @property User $owner
 * @property Category[] $category
 * @property ItemHasMedia[] $itemHasMedia
 * @property Media[] $media
 * @property ItemHasFeature[] $itemHasFeatures
 * @property Feature[] $features
 * @property Location[] $locatio
 * @property ItemHasFeatureSingular[] $itemHasFeatureSingulars
 * @property Feature[] $singularFeatures
 * @property ItemSimilarity[] $itemSimilarities
 * @property ItemSimilarity[] $itemSimilarities0
 * @property Review[] $reviews
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [
                [
                    'price_day',
                    'price_week',
                    'price_month',
                    'owner_id',
                    'condition',
                    'currency_id',
                    'is_available',
                    'location_id',
                    'created_at',
                    'updated_at',
                    'min_renting_days',
                    'category_id'
                ],
                'integer'
            ],
            [['created_at', 'updated_at', 'category_id'], 'required'],
            [['name'], 'string', 'max' => 140],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::className(),
                'targetAttribute' => ['category_id' => 'id']
            ],
            [
                ['currency_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Currency::className(),
                'targetAttribute' => ['currency_id' => 'id']
            ],
            [
                ['location_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Location::className(),
                'targetAttribute' => ['location_id' => 'id']
            ],
            [
                ['owner_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['owner_id' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
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
            'location_id' => Yii::t('app', 'Location ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'min_renting_days' => Yii::t('app', 'Min Renting Days'),
            'category_id' => Yii::t('app', 'Category ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['item_id' => 'id']);
    }

    /**
     * @return int
     */
    public function getBookingsCount()
    {
        return $this->hasMany(Booking::className(), ['item_id' => 'id'])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemHasMedia()
    {
        return $this->hasMany(ItemHasMedia::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['id' => 'media_id'])->viaTable('item_has_media',
            ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemHasFeatures()
    {
        return $this->hasMany(ItemHasFeature::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeatures()
    {
        return $this->hasMany(Feature::className(), ['id' => 'feature_id'])->viaTable('item_has_feature',
            ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemHasFeatureSingulars()
    {
        return $this->hasMany(ItemHasFeatureSingular::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSingularFeatures()
    {
        return $this->hasMany(Feature::className(), ['id' => 'feature_id'])->viaTable('item_has_feature_singular',
            ['item_id' => 'id'])->where(['feature.is_singular' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNonSingularFeatures()
    {
        return $this->hasMany(Feature::className(), ['id' => 'feature_id'])->viaTable('item_has_feature_singular',
            ['item_id' => 'id'])->where(['feature.is_singular' => 0]);
    }


}
