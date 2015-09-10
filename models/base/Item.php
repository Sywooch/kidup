<?php

namespace app\models\base;

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
 *
 * @property \app\models\Booking[] $bookings
 * @property \app\models\User $owner
 * @property \app\models\Currency $currency
 * @property \app\models\Location $location
 * @property \app\models\ItemHasCategory[] $itemHasCategories
 * @property \app\models\Category[] $categories
 * @property \app\models\ItemHasMedia[] $itemHasMedia
 * @property \app\models\Media[] $media
 * @property \app\models\ItemSimilarity[] $itemSimilarities
 * @property \app\models\ItemSimilarity[] $itemSimilarities0
 * @property \app\models\Review[] $reviews
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
            [['price_day', 'price_week', 'price_month', 'owner_id', 'condition', 'currency_id', 'is_available', 'location_id', 'created_at', 'updated_at', 'min_renting_days'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['name'], 'string', 'max' => 140],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'id']]
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(\app\models\Booking::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'owner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(\app\models\Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(\app\models\Location::className(), ['id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemHasCategories()
    {
        return $this->hasMany(\app\models\ItemHasCategory::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(\app\models\Category::className(), ['id' => 'category_id'])->viaTable('item_has_category', ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemHasMedia()
    {
        return $this->hasMany(\app\models\ItemHasMedia::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(\app\models\Media::className(), ['id' => 'media_id'])->viaTable('item_has_media', ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemSimilarities()
    {
        return $this->hasMany(\app\models\ItemSimilarity::className(), ['item_id_2' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemSimilarities0()
    {
        return $this->hasMany(\app\models\ItemSimilarity::className(), ['item_id_1' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(\app\models\Review::className(), ['item_id' => 'id']);
    }




}
