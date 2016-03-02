<?php
namespace codecept\functional\booking;

use notifications\components\MailRenderer;
use functionalTester;
use codecept\_support\MuffinHelper;
use codecept\muffins\Booking;
use codecept\muffins\Item;
use codecept\muffins\User;
use codecept\muffins\Message;
use League\FactoryMuffin\FactoryMuffin;
use notifications\models\UrlFactory;
use Yii;

/**
 * Functional test for mail rendering.
 *
 * Class RenderCest
 * @package codecept\functional\mail
 */
class RenderCestOld
{

    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }

    public function testPartialRender(FunctionalTester $I)
    {
        $user = $this->fm->create(User::class);
        $mail = (new \mail\mails\user\WelcomeFactory())->create($user);
        $renderer = new MailRenderer($mail);
        $I->assertTrue(strlen($renderer->renderPartial()) > 0);
    }

}

?>