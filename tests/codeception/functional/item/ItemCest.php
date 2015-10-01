<?php
namespace app\tests\codeception\functional\item;

use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\_support\UserHelper;
use app\tests\codeception\muffins\Item;
use app\tests\codeception\muffins\User;
use functionalTester;
use Faker\Factory as Faker;
use League\FactoryMuffin\FactoryMuffin;
use Yii;

/**
 * Functional test for the booking module.
 *
 * Class BookingCest
 * @package app\tests\codeception\functional\booking
 */
class ItemCest {

    /**
     * @var FactoryMuffin
     */
    private $fm = null;

    public function _before() {
        $this->fm = (new MuffinHelper())->init()->getFactory();
    }

    public function makeBooking(FunctionalTester $I) {
        $faker = Faker::create();
        $format = 'd-m-Y';

        // generate random dates
        $dateFrom = $faker->dateTimeBetween('+2 days', '+3 days')->getTimestamp();
        $dateTo = $faker->dateTimeBetween('+5 days', '+8 days')->getTimestamp();

        // calculate the number of days between these dates
        $numDays = floor($dateTo / 3600 / 24) - floor($dateFrom / 3600 / 24);

        // define the users and the item
        $renter = $this->fm->create(User::class);
        $owner = $this->fm->create(User::class);
        $item = $this->fm->create(Item::class);
        $item->owner_id = (int)$owner->id;
        $item->save();
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
        $I->canSee($numDays . ' days');
        $I->canSee('Request to Book');

        // go to the action page of the form
        $I->amOnPage('/item/' . $item->id . '?' . http_build_query($params) . '&_book=1');
        $I->canSee('Review and book');
    }

}

?>