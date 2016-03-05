<?php
namespace notification\components;

use booking\models\booking\Booking;
use message\models\message\Message;
use user\models\User;
use Yii;

class NotificationImplementationError extends \app\extended\base\Exception
{
}

;

class NotificationDistributer
{
    private $userAllowsPush = false;

    /**
     * Construct a notification distributor for a certain user id
     * NotificationDistributer constructor.
     * @param $userId
     */
    public function __construct($userId)
    {
        $user = User::findOneOr404($userId);
        $this->userAllowsPush = $user->getUserAcceptsPushNotifications();
    }

    private function newRenderer($template)
    {
        $isPushTemplate = isset(PushTemplates::$templates[$template]);
        $isMailTemplate = isset(MailTemplates::$templates[$template]) || (isset(MailTemplates::$templates[$template]));
        if (!$isMailTemplate && !$isPushTemplate) {
            throw new NotificationImplementationError("Template not found");
        }
        if (!$isMailTemplate && $isPushTemplate && !$this->userAllowsPush) {
            return false;
        }
        if ($this->userAllowsPush && $isPushTemplate) {
            return new PushRenderer($template);
        } else {
            return new MailRenderer($template);
        }
    }

    private function toSender($renderer)
    {
        if ($this->userAllowsPush) {
            return PushSender::send($renderer);
        } else {
            return MailSender::send($renderer);
        }
    }

    private function load($templateName, $contents)
    {
        $renderer = $this->newRenderer($templateName);
        if (!$renderer) {
            return false;
        }
        foreach ($contents as $content => $value) {
            switch ($content) {
                case "booking":
                    $renderer->loadBooking($value);
                    break;
                case "user":
                    $renderer->loadUser($value);
                    break;
                case "message":
                    $renderer->loadMessage($value);
                    break;
            }
        }
        return $this->toSender($renderer);
    }

    public function bookingConfirmedOwner(Booking $booking)
    {
        return $this->load("booking_confirmed_owner", [
            'booking' => $booking
        ]);
    }

    public function bookingConfirmedRenter(Booking $booking)
    {
        return $this->load("booking_confirmed_renter", [
            'booking' => $booking
        ]);
    }

    public function bookingDeclinedRenter(Booking $booking)
    {
        return $this->load("booking_declined_renter", [
            'booking' => $booking
        ]);
    }

    public function bookingPayoutOwner(Booking $booking)
    {
        return $this->load("booking_payout_owner", [
            'booking' => $booking
        ]);
    }

    public function bookingRequestOwner(Booking $booking)
    {
        return $this->load("booking_request_owner", [
            'booking' => $booking
        ]);
    }

    public function bookingStartOwner(Booking $booking)
    {
        return $this->load("booking_start_owner", [
            'booking' => $booking
        ]);
    }

    public function bookingStartRenter(Booking $booking)
    {
        return $this->load("booking_start_renter", [
            'booking' => $booking
        ]);
    }

    public function userReconfirm(User $user)
    {
        return $this->load("user_reconfirm", [
            'user' => $user
        ]);
    }

    public function userRecovery(User $user)
    {
        return $this->load("user_recovery", [
            'user' => $user
        ]);
    }

    public function userWelcome(User $user)
    {
        return $this->load("user_welcome", [
            'user' => $user
        ]);
    }

    public function conversationMessageReceived(Message $message)
    {
        return $this->load("conversation_message_received", [
            'message' => $message
        ]);
    }
}