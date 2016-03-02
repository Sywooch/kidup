<?php
namespace notification\components;

use booking\models\Booking;
use booking\models\Payout;
use Yii;

class Renderer
{

    // E-mail sender
    public $sender_name = null;
    public $sender_email = null;

    // E-mail receiver
    public $receiver_name = null;
    public $receiver_email = null;
    public $receiver_language = null;

    // Item renter
    public $contact_renter_url = null;
    public $renter_phone_url = null;
    public $renter_email_url = null;
    public $renter_username = null;
    public $renter_name = null;

    // Booking
    public $booking_id = null;
    public $accept_url = null;
    public $decline_url = null;
    public $booking_date = null;
    public $booking_start_date = null;
    public $booking_end_date = null;

    // Item owner
    public $contact_owner_url;
    public $owner_username = null;
    public $owner_name = null;

    // Conversation
    public $sender_username = null;

    // Item
    public $finish_product_url = null;
    public $rent_url = null;
    public $rent_out_url = null;
    public $item_name = null;
    public $total_payout_amount = null;
    public $time_before = null;

    // Review
    public $reviewer_username = null;
    public $days_left = null;
    public $review_url = null;

    // User
    public $confirm_url = null;
    public $recovery_url = null;
    public $profile_url = null;
    public $social_media_url = null;

    // General
    public $app_url = null;
    public $email_support = null;
    public $faq_url = null;
    public $title = null;

    // Templating
    protected $templateFolder = null;

    public function renderFromFile($template) {
        $vars = $this->getVariables();
        return \Yii::$app->view->renderFile($this->templateFolder . '/' . $template . '.twig', $vars);
    }

    public function getVariables() {
        $vars = [];
        foreach ($this as $key => $value) {
            $vars[$key] = $value;
        }
        return $vars;
    }

    public function setVariables($vars) {
        foreach ($vars as $key => $value) {
            $this->$key = $value;
        }
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function loadBooking(Booking $booking) {
        // Renter
        $this->renter_name = $booking->renter->profile->getName();
        //$this->renter_username = $booking->renter->username;

        // Owner
        $this->owner_name = $booking->item->owner->profile->getName();
        //$this->owner_username = $booking->item->owner->username;

        // Booking
        $this->booking_start_date = $booking->time_from;
        $this->booking_end_date= $booking->time_to;

        // Item
        $this->item_name = $booking->item->name;
    }

    public function loadPayout(Payout $payout) {
        // Payout
        $this->total_payout_amount = $payout->amount;

        // Booking
    }

    public function fillAutomatically() {
        $booking = Payout::find()->one();
        $this->loadPayout($booking);
    }

}