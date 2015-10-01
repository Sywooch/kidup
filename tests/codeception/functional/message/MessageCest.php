<?php
namespace app\tests\codeception\functional\message;

use app\tests\codeception\_support\FixtureHelper;
use app\tests\codeception\_support\MessageHelper;
use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\_support\UserHelper;
use app\tests\codeception\muffins\Conversation;
use app\tests\codeception\muffins\Message;
use app\tests\codeception\muffins\User;
use FunctionalTester;
use League\FactoryMuffin\FactoryMuffin;

/**
 * Functional test for the message module.
 *
 * Class MessageCest
 * @package app\tests\codeception\functional\message
 */
class MessageCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm;

    public function _before() {
        $this->fm = (new MuffinHelper())->init();
    }

    /**
     * Test whether the badge count is displayed correctly on the home page.
     *
     * @param FunctionalTester $I
     */
    public function testBadgeCount(FunctionalTester $I)
    {
        $initiater = $this->fm->create(User::class);
        $receiver = $this->fm->create(User::class);

        $I->wantTo('ensure that the badge count is displayed correctly on the home page.');
        UserHelper::login($receiver);
        $I->amOnPage('/');
        $I->dontSeeElement('.message .badge');

        // now insert a fake message
        $conversation = $this->fm->create(Conversation::class);
        $conversation->initiater_user_id = $initiater->id;
        $conversation->target_user_id = $receiver->id;
        $message = $this->fm->create(Message::class);
        $message->conversation_id = $conversation->id;
        $message->sender_user_id = $initiater->id;
        $message->receiver_user_id = $receiver->id;
        $message->save();
        $I->amOnPage('/');
        $I->canSeeElement('.message .badge');
        $I->canSee('1', '.message .badge');

        // now set the message to read
        $message->read_by_receiver = true;
        $message->save();
        $I->amOnPage('/');
        $I->dontSeeElement('.message .badge');
    }

}

?>