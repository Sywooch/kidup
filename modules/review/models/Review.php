<?php

namespace review\models;

use app\helpers\Event;
use \booking\models\Booking;
use Carbon\Carbon;
use Yii;

/**
 * This is the model class for table "review".
 */
class Review extends base\Review
{
    // the written reviews
    const TYPE_USER_PUBLIC = 'public_user';
    const TYPE_USER_PRIVATE = 'private_user';

    // the rated reviews
    const TYPE_USER_COMMUNICATION = 'user_communication';
    const TYPE_USER_EXCHANGE = 'user_exchange';
    const TYPE_USER_RECOMMEND = 'user_recommend';
    const TYPE_EXPERIENCE = 'experience';

    // for the owner
    const TYPE_USER_HANDLING = 'handling';

    // for the renter
    const TYPE_AD_ACCURACY = 'ad_accuracy';

    const TYPE_PRIVATE_KIDUP = 'private_kidup';


    public static function create($type, $value, Booking $booking, $isOwner = false)
    {
        $review = new Review();
        $review->value = (string) $value;
        $review->type = $type;
        $review->booking_id = $booking->id;
        $review->item_id = $booking->item->id;
        if ($isOwner) {
            $review->reviewer_id = $booking->item->owner->id;
            $review->reviewed_id = $booking->renter_id;
        } else {
            $review->reviewer_id = $booking->renter_id;
            $review->reviewed_id = $booking->item->owner->id;
        }

        return $review->save();
    }

    /**
     * Checks is the reviews can be made public, and publishes them if true
     * @param Booking $booking
     */
    public function checkForPublication(Booking $booking)
    {
        $reviewsRenter = Review::find()->where([
            'booking_id' => $booking->id,
            'reviewer_id' => $booking->renter_id,
            'is_public' => 0
        ])->all();

        $reviewsOwner = Review::find()->where([
            'booking_id' => $booking->id,
            'reviewer_id' => $booking->item->owner_id,
            'is_public' => 0
        ])->all();

        if (count($reviewsOwner) > 1 && count($reviewsRenter) > 1) {
            // make them all public
            $reviews = array_merge($reviewsOwner, $reviewsRenter);
            foreach ($reviews as $review) {
                $review->is_public = 1;
                $review->save();
            }
            Event::trigger($booking, Booking::EVENT_REVIEWS_PUBLIC); // trigger emails
            return true;
        }

        return false;
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
        }
        $this->updated_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;

        return parent::beforeValidate();
    }

    public function afterSave($insert, $changedAttrs)
    {
        if ($this->type == self::TYPE_PRIVATE_KIDUP && $this->value != '') {
            \Yii::$app->slack->send("User $this->reviewer_id had kidup feedback on booking $this->booking_id:
    $this->value
            ");
        }
        return parent::afterSave($insert, $changedAttrs);
    }
}
