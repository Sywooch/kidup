<?php
namespace mail\mails\review;

use booking\models\Booking;
use mail\models\UrlFactory;

/**
 * Base class for the review emails
 */
class RequestFactory
{
    public function create(Booking $booking, $isOwner)
    {
        $mail = new Request();
        $mail->reviewUrl = UrlFactory::review($booking);
        if (!$isOwner) {
            $mail->emailAddress = $booking->renter->email;
            $mail->otherName = $booking->item->owner->profile->getFullName();
        } else {
            $mail->emailAddress = $booking->item->owner->email;
            $mail->otherName = $booking->renter->profile->getFullName();
        }
        $mail->subject = \Yii::t("mail.review_request.header", "Write a review for {userName}",[
            'userName' => $mail->otherName
        ]);
        return $mail;
    }
}