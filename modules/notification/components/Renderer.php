<?php
namespace notification\components;

// @todo: messaging tab = profielpagina van een persoon
// mail for mail
use booking\models\booking\Booking;
use booking\models\payout\Payout;
use item\models\item\Item;
use Carbon\Carbon;
use message\models\message\Message;
use notification\components\renderer\BookingRenderer;
use notification\components\renderer\MessageRenderer;
use notification\components\renderer\ItemRenderer;
use notification\components\renderer\PayoutRenderer;
use notification\components\renderer\UserRenderer;
use user\models\User;
use Yii;

class Renderer
{

    public $type = null;

    /** @var BookingRenderer */
    public $bookingRenderer;
    /** @var PayoutRenderer */
    public $payoutRenderer;
    /** @var ItemRenderer */
    public $itemRenderer;
    /** @var UserRenderer */
    public $userRenderer;
    /** @var MessageRenderer */
    public $messageRenderer;

    // Variables
    public $vars = [];

    // Templating
    protected $templateFolder = null;

    public function __construct($template = null) {
        \Yii::$app->language = 'da-dk';
        $this->template = $template;
        $this->bookingRenderer = new BookingRenderer();
        $this->itemRenderer = new ItemRenderer();
        $this->payoutRenderer = new PayoutRenderer();
        $this->userRenderer = new UserRenderer();
        $this->messageRenderer = new MessageRenderer();
        $this->setVariables([
            'app_url' => self::inAppLink('/home'),
            'faq_url' => self::inAppLink('/home'),
            'rent_url' => self::inAppLink('/item/home'),
            'rent_out_url' => self::inAppLink('/item/create'),
            'social_media_url' => 'https://www.facebook.com/kidup.social',
            'email_support' => 'philip@kidup.dk',
        ]);
    }

    public function renderFromFile($template) {
        $vars = $this->getVariables();

        return \Yii::$app->view->renderFile($this->templateFolder . '/' . $template . '.twig', $vars);
    }

    public function getVariables() {
        return $this->vars;
    }

    public function setVariables($vars) {
        $this->vars = array_merge($this->vars, $vars);
    }

    public function getReceiverEmail() {
        return $this->vars['receiver_email'];
    }

    public function getUserId() {
        return $this->vars['user_id'];
    }

    /**
     * Load the booking.
     *
     * @param Booking $booking
     */
    public function loadBooking(Booking $booking) {
        $vars = $this->bookingRenderer->loadBooking($booking);
        $this->setVariables($vars);
        $vars = $this->userRenderer->loadRenter($booking->renter);
        $this->setVariables($vars);
        $vars = $this->userRenderer->loadOwner($booking->item->owner);
        $this->setVariables($vars);
    }

    /**
     * Load the payout.
     *
     * @param Payout $payout
     */
    public function loadPayout(Payout $payout) {
        $vars = $this->payoutRenderer->loadPayout($payout);
        $this->setVariables($vars);
    }

    /**
     * Load the item.
     *
     * @param Item $item
     */
    public function loadItem(Item $item) {
        $vars = $this->itemRenderer->loadItem($item);
        $this->setVariables($vars);
    }

    /**
     * Load the user.
     *
     * @param User $user
     */
    public function loadUser(User $user) {
        $vars = $this->userRenderer->loadUser($user);
        $this->setVariables($vars);
    }

    /**
     * Load a message.
     *
     * @param Message $message
     */
    public function loadMessage(Message $message) {
        $vars = $this->messageRenderer->loadMessage($message);
        $this->setVariables($vars);
        $vars = $this->userRenderer->loadSender($message->conversation->initiaterUser);
        $this->setVariables($vars);
        $vars = $this->userRenderer->loadReceiver($message->conversation->targetUser);
        $this->setVariables($vars);
    }

    /**
     * Display a UNIX timestamp in a conventional way.
     *
     * @param int $unixTimestamp The UNIX timestamp.
     * @return string The conventional display of the timestamp.
     */
    public static function displayDateTime($unixTimestamp) {
        return Carbon::createFromTimestamp($unixTimestamp)->format("d-m-y H:i");
    }

    /**
     * Make an in-app link.
     *
     * @param string $url In-app URL to go to.
     * @return string HTTP link which opens the in-app URL.
     */
    public static function inAppLink($url) {
        return \yii\helpers\Url::to('@web/mail/click?mailId=0&url=' . base64_encode('kidup://' . $url), true);
    }

    /**
     * Get the template which is being rendered.
     *
     * @return String filename of the template.
     */
    public function getTemplate()
    {
        return $this->template;
    }

}