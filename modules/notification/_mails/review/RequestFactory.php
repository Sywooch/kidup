<?php
namespace mail\mails\review;

use booking\models\Booking;
use notifications\models\UrlFactory;

/**
 * Base class for the review emails
 */
class RequestFactory
{
    public function create(Booking $booking, $isOwner)
    {
        $e = new Request();
        $e->setSubject('Review request');
        $e->reviewUrl = UrlFactory::review($booking);
        if (!$isOwner) {
            $e->emailAddress = $booking->renter->email;
            $e->otherName = $booking->item->owner->profile->getFullName();
        } else {
            $e->emailAddress = $booking->item->owner->email;
            $e->otherName = $booking->renter->profile->getFullName();
        }
        $receiver = (new \mail\components\MailUserFactory())->create($e->otherName, $e->emailAddress);
        $e->setReceiver($receiver);
        $e->subject = \Yii::t("mail.review_request.header", "Write a review for {userName}", [
            'userName' => $e->otherName
        ]);
        return $e;
    }
}