<?php
namespace mail\mails;

use Carbon\Carbon;
use mail\models\Mailer;
use Yii;
use yii\helpers\Url;

class Review extends Mailer
{
    /**
     * Requested to make a review
     * @param \booking\models\Booking $booking
     * @return bool
     */
    public function request($data)
    {
        $booking = $data['booking'];
        $isOwner = $data['isOwner'];
        if (!$isOwner) {
            $user = $booking->renter;
            $otherName = $booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name;
        } else {
            $user = $booking->item->owner;
            $otherName = $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name;
        }
        return $this->sendMessage([
            'email' => $user->email,
            'subject' => "Skriv en omtale " . $otherName,
            'type' => self::REVIEW_REQUEST,
            'params' => [
                'otherName' => $otherName,
                'profileName' => $user->profile->first_name,
            ],
            'urls' => [
                'review' => Url::to('@web/review/create/' . $booking->id, true),
            ]
        ]);
    }

    /**
     * Reviews aare published
     * @param \booking\models\Booking $booking
     * @return bool
     */
    public function published($data)
    {
        $booking = $data['booking'];
        $isOwner = $data['isOwner'];
        if (!$isOwner) {
            $user = $booking->renter;
            $otherName = $booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name;
        } else {
            $user = $booking->item->owner;
            $otherName = $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name;
        }
        return $this->sendMessage([
            'email' => $user->email,
            'subject' => "Skriv en omtale " . $otherName,
            'type' => self::REVIEW_PUBLIC,
            'params' => [
                'otherName' => $otherName,
                'profileName' => $user->profile->first_name,
            ],
            'urls' => [
                'profile' => Url::to('@web/user/' . $user->id, true),
            ]
        ]);
    }

    /**
     * Reminder
     * @param \booking\models\Booking $booking
     * @param \user\models\User $user
     * @return bool
     */
    public function reminder($data)
    {
        $booking = $data['booking'];
        $isOwner = $data['isOwner'];
        if (!$isOwner) {
            $user = $booking->renter;
            $otherName = $booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name;
        } else {
            $user = $booking->item->owner;
            $otherName = $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name;
        }
        return $this->sendMessage([
            'email' => $user->email,
            'subject' => "Skriv en omtale " . $otherName,
            'type' => self::REVIEW_REMINDER,
            'params' => [
                'otherName' => $otherName,
                'profileName' => $user->profile->first_name,
                'daysLeft' => Carbon::createFromTimestamp($booking->time_to)->addDays(14)->diffInDays(Carbon::now())
            ],
            'urls' => [
                'review' => Url::to('@web/review/create/' . $booking->id, true),
            ]
        ]);
    }
}