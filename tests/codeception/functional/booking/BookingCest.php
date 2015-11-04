<?php
namespace codecept\functional\booking;

use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\_support\YiiHelper;
use codecept\muffins\Item;
use codecept\muffins\User;
use booking\models\Payin;
use functionalTester;
use Faker\Factory as Faker;
use item\controllers\CronController;
use League\FactoryMuffin\FactoryMuffin;
use message\models\Message;
use Yii;

/**
 * Functional test for the booking module.
 *
 * Class BookingCest
 * @package codecept\functional\booking
 */
class BookingCest {

    /**
     * @var FactoryMuffin
     */
    private $item;
    protected $fm = null;

    public function _before() {
        $this->fm = (new MuffinHelper())->init();
    }

    public function testBookingProcess(FunctionalTester $I){
        $renter = $this->startBooking($I);
        $this->completeBookingRequest($I, $renter);
    }

    private function startBooking(FunctionalTester $I) {
        $faker = Faker::create();
        $format = 'd-m-Y';

        // generate random dates
        $dateFrom = $faker->dateTimeBetween('+2 days', '+3 days')->getTimestamp();
        $dateTo = $faker->dateTimeBetween('+5 days', '+8 days')->getTimestamp();

        // calculate the number of days between these dates
        $numDays = floor($dateTo / 3600 / 24) - floor($dateFrom / 3600 / 24);

        /**
         * @var \item\models\Item $item
         */
        $item = $this->fm->create(Item::class);
        $this->item = $item;
        $renter = $this->fm->create(User::class);
        UserHelper::login($renter);

        $params = [
            'create-booking' => [
                'dateFrom' => date($format, $dateFrom),
                'dateTo' => date($format, $dateTo),
            ]
        ];

        // check the generated table
        $I->amOnPage('/item/' . $item->id . '?' . http_build_query($params));
        $I->canSee('Service fee');
//        $I->canSee(($numDays+1) . ' days');
        $I->canSee('Request to Book');

        // go to the action page of the form
        $I->amOnPage('/item/' . $item->id . '?' . http_build_query($params) . '&_book=1');
        $I->canSee('Review and book');
        return $renter;
    }

    private function completeBookingRequest(FunctionalTester $I, $renter) {
        UserHelper::login($renter);

        // check the generated table
        $I->canSee('Secure Booking - Pay in 1 Minute');
        $I->canSee('Message to ');
        $I->see('Review and book');
        $I->fillField('#confirm-booking-message', 'testmessage');
        $I->checkOption('#confirm-booking-rules');
        $emailCountBefore = count(YiiHelper::listEmails());
        $I->click('Book now');

        $booking = $this->item->bookings[0];
        $I->amOnPage('/booking/' . $booking->id);
        $I->seeRecord(Payin::class, [
            'id' => $booking->payin_id
        ]);

        // check whether there was sent a message to the product owner
        $I->seeRecord(Message::class, [
            'receiver_user_id' => $booking->item->owner_id,
            'sender_user_id' => $booking->renter_id,
            'message' => 'testmessage'
        ]);

        // there should be some e-mails in the queue
        $emailCountAfter = count(YiiHelper::listEmails());
        $emailDeltaCount = $emailCountAfter - $emailCountBefore;
        // new conversation mail, renter confirmation, booker request email
//        $I->assertEquals($emailDeltaCount, 3);
    }
}
?>