<?php
namespace mail\mails\review;

use booking\models\Booking;
use Carbon\Carbon;
use notifications\models\UrlFactory;

/**
 * Base class for the review emails
 */
class ReminderFactory
{
    public function create(Booking $booking, $isOwner)
    {
        $mail = new Reminder();
        $mail->setSubject('Review reminder');
        $mail->daysLeft = Carbon::createFromTimestamp($booking->time_to)->addDays(14)->diffInDays(Carbon::now());
        if (!$isOwner) {
            $mail->emailAddress = $booking->renter->email;
            $mail->otherName = $booking->item->owner->profile->getFullName();
        } else {
            $mail->emailAddress = $booking->item->owner->email;
            $mail->otherName = $booking->renter->profile->getFullName();
        }
        $receiver = (new \mail\components\MailUserFactory())->create($mail->otherName, $mail->emailAddress);
        $mail->setReceiver($receiver);
        $mail->reviewUrl = UrlFactory::review($booking);
        $mail->subject = \Yii::t("mail.review_reminder.header", "Reminder: review {userName}", [
            'userName' => $mail->otherName
        ]);
        return $mail;
    }
}