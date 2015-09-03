<?php
namespace app\tests\codeception\functional\message;

use app\tests\codeception\_support\FixtureHelper;
use app\tests\codeception\_support\MessageHelper;
use app\tests\codeception\_support\UserHelper;
use FunctionalTester;

/**
 * Functional test for the message module.
 *
 * Class MessageCest
 * @package app\tests\codeception\functional\message
 */
class MessageCest
{

    private $messageHelper = null;

    /**
     * Initialize the test.
     *
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        (new FixtureHelper)->fixtures();
        $this->messageHelper = new MessageHelper();
    }

    /**
     * Remove all created data.
     *
     * @param FunctionalTester $I
     */
    public function _after(FunctionalTester $I)
    {
        $this->messageHelper->clearConversations();
    }

    /**
     * Test whether the badge count is displayed correctly on the home page.
     *
     * @param FunctionalTester $I
     */
    public function testBadgeCount(FunctionalTester $I)
    {
        $I->wantTo('ensure that the badge count is displayed correctly on the home page.');
        UserHelper::login($I, 'simon@kidup.dk', 'testtest');
        $I->amOnPage('/');
        $I->canSeeElement('.message .badge');
        $I->canSee('', '.message .badge');

        // now insert a fake message
        $message = $this->messageHelper->createMessage(2, 1, 'Test message');
        $I->amOnPage('/');
        $I->canSeeElement('.message .badge');
        $I->canSee('1', '.message .badge');

        // now set the message to read
        $message->read_by_receiver = true;
        $message->save();
        $I->amOnPage('/');
        $I->canSeeElement('.message .badge');
        $I->canSee('', '.message .badge');

        $this->messageHelper->clearConversations();
    }

}

?>