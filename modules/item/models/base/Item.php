<?php

namespace item\models\base;

use booking\models\Booking;
use item\models\Category;
use review\models\Review;
use user\models\base\Currency;
use user\models\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "item".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $price_day
 * @property integer $price_week
 * @property integer $price_month
 * @property integer $price_year
 * @property integer $owner_id
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
 * @property Category $category
 * @property ItemHasMedia[] $itemHasMedia
 * @property Media[] $media
 * @property ItemHasItemFacet[] $itemHasItemFacets
 * @property ItemFacet[] $itemFacets
 * @property ItemFacet[] $singularItemFacets
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

    public function rules()
    {
        return [
            [['description'], 'string'],
            [
                [
                    'price_day',
                    'price_week',
                    'price_month',
                    'price_year',
                    'owner_id',
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
            [['created_at', 'updated_at', 'owner_id', 'location_id'], 'required'],
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
                'targetClass' => Location::className(),
                'targetAttribute' => ['location_id' => 'id']
            ],
            [['price_day', 'price_week', 'price_month', 'price_year'], 'integer', 'min' => 0, 'max' => 999999],
            [['description'], 'string', 'min' => 2],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('item.attributes.id', 'ID'),
            'name' => Yii::t('item.attributes.name', 'Name'),
            'description' => Yii::t('item.attributes.description', 'Description'),
            'price_day' => Yii::t('item.attributes.price_day', 'Price Day'),
            'price_week' => Yii::t('item.attributes.price_week', 'Price Week'),
            'price_month' => Yii::t('item.attributes.price_month', 'Price Month'),
            'price_year' => Yii::t('item.attributes.price_year', 'Price Year'),
            'owner_id' => Yii::t('item.attributes.owner_id', 'Owner'),
            'currency_id' => Yii::t('item.attributes.currency_id', 'Currency'),
            'is_available' => Yii::t('item.attributes.is_available', 'Is Available'),
            'location_id' => Yii::t('item.attributes.location_id', 'Location'),
            'created_at' => Yii::t('item.attributes.created_at', 'Created At'),
            'updated_at' => Yii::t('item.attributes.updated_at', 'Updated At'),
            'min_renting_days' => Yii::t('item.attributes.min_renting_days', 'Min Renting Days'),
            'category_id' => Yii::t('item.attributes.category_id', 'Category'),
        ];
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            'create' => ['owner_id', 'is_available', 'category_id'],
            'default' => [
                'name',
                'description',
                'price_week',
                'owner_id',
                'currency_id',
                'category_id',
                'location_id'
            ],
            'validate' => [
                'name',
                'description',
                'price_week',
                'owner_id',
                'currency_id',
                'category_id',
                'location_id'
            ],
            'location' => ['location_id']
        ]);
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
        return $this->hasMany(ItemHasMedia::className(), ['item_id' => 'id'])->orderBy('order ASC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['id' => 'media_id'])
            ->viaTable('item_has_media', ['item_id' => 'id']);
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
    public function getItemHasItemFacets()
    {
        return $this->hasMany(ItemHasItemFacet::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemFacets()
    {
        return $this->hasMany(ItemFacet::className(), ['id' => 'item_facet_id'])->viaTable('item_has_item_facet',
            ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSingularItemFacets()
    {
        return $this->hasMany(ItemFacet::className(),
            ['id' => 'item_facet_id'])->viaTable('item_has_item_facet_singular',
            ['item_id' => 'id'])->where(['item_facet.is_singular' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNonSingularItemFacets()
    {
        return $this->hasMany(ItemFacet::className(),
            ['id' => 'item_facet_id'])->viaTable('item_has_item_facet_singular',
            ['item_id' => 'id'])->where(['item_facet.is_singular' => 0]);
    }


}
