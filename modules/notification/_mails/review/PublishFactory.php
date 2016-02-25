<?php
namespace mail\mails\review;

use booking\models\Booking;
use notifications\components\MailUserFactory;
use notifications\models\UrlFactory;

/**
 * Base class for the review emails
 */
class PublishFactory
{
    public function create(Booking $booking, $isOwner)
    {
        $mail = new Publish;
        if (!$isOwner) {
            $mail->emailAddress = $booking->renter->email;
            $mail->profileUrl = UrlFactory::profile($booking->renter);
            $mail->otherName = $booking->item->owner->profile->getFullName();
        } else {
            $mail->emailAddress = $booking->item->owner->email;
            $mail->profileUrl = UrlFactory::profile($booking->item->owner);
            $mail->otherName = $booking->renter->profile->getFullName();
        }
        $receiver = (new \mail\components\MailUserFactory())->create($mail->otherName, $mail->emailAddress);
        $mail->setReceiver($receiver);
        $mail->subject = \Yii::t("mail.review_publish.header", "{userName} has reviewed you!", [
            'userName' => $mail->otherName
        ]);
        return $mail;
    }
}