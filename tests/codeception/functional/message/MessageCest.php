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
        /**
         * @var \message\models\Conversation $conversation
         */
        $conversation = $this->fm->create(Conversation::class);

        $I->wantTo('ensure that the badge count is displayed correctly on the home page.');
        UserHelper::login($conversation->targetUser);
        $I->amOnPage('/');
        $I->dontSeeElement('.message .badge');

        // now insert a fake message
        $message = $this->fm->create(Message::class,[
            'conversation_id' => $conversation->id,
            'receiver_user_id'=> $conversation->target_user_id
        ]);

        $I->amOnPage('/');
        $I->canSeeElement('.message .badge');
        $I->canSee('1', '.message .badge');

        // now set the message to read by going to the inbox

        $I->amOnPage('/inbox/'.$conversation->id);
        $message->read_by_receiver = true;
        $message->save();
        $I->amOnPage('/');
        $I->dontSeeElement('.message .badge');
    }
}

?>