<?php
namespace mail\mails\review;

use booking\models\Booking;
use Carbon\Carbon;
use mail\models\UrlFactory;

/**
 * Base class for the review emails
 */
class ReminderFactory
{
    public function create(Booking $booking, $isOwner)
    {
        $mail = new Reminder();
        $mail->daysLeft = Carbon::createFromTimestamp($booking->time_to)->addDays(14)->diffInDays(Carbon::now());
        if (!$isOwner) {
            $mail->emailAddress = $booking->renter->email;
            $mail->otherName = $booking->item->owner->profile->getFullName();
        } else {
            $mail->emailAddress = $booking->item->owner->email;
            $mail->otherName = $booking->renter->profile->getFullName();
        }
        $mail->reviewUrl = UrlFactory::review($booking);
        $mail->subject = \Yii::t("mail.review_reminder.header", "Reminder: review {userName}", [
            'userName' => $mail->otherName
        ]);
        return $mail;
    }
}