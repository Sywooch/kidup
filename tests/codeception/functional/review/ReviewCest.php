<?php
namespace tests\functional\item;

use app\modules\booking\models\Booking;
use app\modules\review\models\Review;
use app\modules\user\tests\codeception\_support\FixtureHelper;
use tests\_support\UserHelper;
use functionalTester;
use Yii;

/**
 * functional test for the item module.
 *
 * Class ItemBookingCest
 * @package tests\functional\item
 */
class ReviewCest
{
//    public $booking;
//    public function _before($event)
//    {
//        Review::deleteAll();
//        $this->booking = (new FixtureHelper())->fixtures()['booking'][0];
//    }
//
//    /**
//     * It should not be possible to write a review for a booking which is not yours.
//     *
//     * @param FunctionalTester $I
//     */
//    public function checkReviewAuthorizationError(FunctionalTester $I)
//    {
//        $I->wantTo('ensure that it is not possible to write a review for a booking which is not mine');
//
//        UserHelper::loginOutsider();
//        $I->amOnPage('/review/create/' . $this->booking->id);
//        $I->dontSeeElement('form #owner-review-public');
//    }
//
//    /**
//     * It should not be possible to write a review before the renting period is over.
//     *
//     * @param FunctionalTester $I
//     */
//    public function checkReviewPeriodError(FunctionalTester $I)
//    {
//        $I->wantTo('ensure that it is not possible to write a review before the renting period is over');
//
//        UserHelper::loginRenter();
//        $I->amOnPage('/review/create/' . $this->booking->id);
//        $I->dontSeeElement('form #owner-review-public');
//    }
//
//    /**
//     * Check whether it is possible to create a new booking (using a fake credit card)
//     * and review it afterwards.
//     *
//     * @param FunctionalTester $I
//     */
//    public function checkReview(FunctionalTester $I)
//    {
//        $I->wantTo('ensure that I can make a review when a booking ended');
//        $this->setRentingPeriodInPast($I, $this->booking);
//
//        $I->amOnPage('/review/create/' . $this->booking->id);
//
//        // fill in the review form
//        $I->seeElement('form #owner-review-public');
//        $I->fillField('form #owner-review-public', 'Cool item (public message)');
//        $I->fillField('form #owner-review-private', 'Cool item (private message)');
//        $I->fillField('form #owner-review-experience', '3');
//        $I->fillField('form #owner-review-communication', '3');
//        $I->fillField('form #owner-review-exchange', '3');
//        $I->fillField('form #owner-review-adaccuracy', '3');
//        $I->fillField('form #owner-review-kidupprivate', ' Cool item (KidUp message)');
//        $I->click('Submit Review');
//
//        // check whether the review exists
//        $I->amOnPage('/user/' . $this->booking->owner->id);
//        $I->see('Cool item (public message)');
//    }
//
//    /**
//     * Set the renting period of a booking to the past.
//     *
//     * @param $I FunctionalTester
//     * @param \app\modules\booking\models\Booking Booking to modify
//     */
//    private function setRentingPeriodInPast($I, Booking $booking)
//    {
//        // now set the date back into the past so it enabled us to write a review
//        $dateFrom2 = time() - 3 * 24 * 60 * 60;
//        $dateTo2 = time() - 1 * 24 * 60 * 60;
//        $booking->time_from = $dateFrom2;
//        $booking->time_to = $dateTo2;
//        $booking->save();
//    }
}
