<?php
namespace notification\components;

use booking\models\booking\Booking;
use user\models\User;
use Yii;

class NotificationImplementationError extends \yii\base\Exception{};

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

    private function newRenderer($template){
        $isMailTemplate = in_array($template, MailTemplates::$templates);
        $isPushTemplate = in_array($template, PushTemplates::$templates);

        if(!$isMailTemplate && !$isPushTemplate){
            throw new NotificationImplementationError("Template not found");
        }
        if(!$isMailTemplate && $isPushTemplate && !$this->userAllowsPush){

        }
        if($this->userAllowsPush){
            return new PushRenderer($template);
        }else{
            return new MailRenderer($template);
        }
    }

    private function toSender(Renderer $renderer){
        if($this->userAllowsPush){
            return PushSender::send($renderer);
        }else{
            return MailSender::send($renderer);
        }
    }

    public function bookingConfirmedOwner(Booking $booking){
        $renderer = $this->newRenderer("booking_confirmed_owner");
        $renderer->loadBooking($booking);
        return $this->toSender($renderer);
    }

    public function bookingConfirmedRenter(Booking $booking){
        $renderer = $this->newRenderer("booking_confirmed_renter");
        $renderer->loadBooking($booking);
        return $this->toSender($renderer);
    }

    public function bookingDeclinedRenter(Booking $booking){
        $renderer = $this->newRenderer("booking_declined_renter");
        $renderer->loadBooking($booking);
        return $this->toSender($renderer);
    }

    public function bookingPayoutOwner(Booking $booking){
        $renderer = $this->newRenderer("booking_payout_owner");
        $renderer->loadBooking($booking);
        return $this->toSender($renderer);
    }

    public function bookingRequestOwner(Booking $booking){
        $renderer = $this->newRenderer("booking_request_owner");
        $renderer->loadBooking($booking);
        return $this->toSender($renderer);
    }

    public function bookingStartOwner(Booking $booking){
        $renderer = $this->newRenderer("booking_start_owner");
        $renderer->loadBooking($booking);
        return $this->toSender($renderer);
    }

    public function bookingStartRenter(Booking $booking){
        $renderer = $this->newRenderer("booking_start_renter");
        $renderer->loadBooking($booking);
        return $this->toSender($renderer);
    }

    public function userReconfirm(User $user){
        $renderer = $this->newRenderer("user_reconfirm");
        $renderer->loadUser($user);
        return $this->toSender($renderer);
    }

    public function userRecovery(User $user){
        $renderer = $this->newRenderer("user_recovery");
        $renderer->loadUser($user);
        return $this->toSender($renderer);
    }

    public function userWelcome(User $user){
        $renderer = $this->newRenderer("user_recovery");
        $renderer->loadUser($user);
        return $this->toSender($renderer);
    }
}