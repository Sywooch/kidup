<?php
namespace mail\mails\item;

use booking\models\Booking;
use item\models\Item;
use mail\models\UrlFactory;

/**
 * Base class for the review emails
 */
class UnfinishedReminderFactory
{
    public function create(Item $item)
    {
        $mail = new UnfinishedReminder();
        $mail->itemName = $item->name;
        $mail->emailAddress = $item->owner->email;
        $mail->itemUrl = UrlFactory::itemCompletion($item);
        $mail->subject = "Byd forældre velkommen, og tjen ekstra penge";
        return $mail;
    }
}