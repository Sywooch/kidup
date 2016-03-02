<?php
namespace codecept\functional\message;

use codecept\_support\FixtureHelper;
use codecept\_support\MessageHelper;
use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\Conversation;
use codecept\muffins\Message;
use codecept\muffins\User;
use FunctionalTester;
use League\FactoryMuffin\FactoryMuffin;

/**
 * Test all message threads and events.
 *
 * Class MessageCest
 * @package codecept\functional\message
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

}

?>