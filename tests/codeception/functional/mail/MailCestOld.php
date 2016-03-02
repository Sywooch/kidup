<?php
namespace codecept\functional\mail;

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
 * Functional test for mails.
 *
 * Class MailCest
 * @package codecept\functional\mail
 */
class MailCestOld
{

    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }

    public function testBookingOwner(FunctionalTester $I)
    {
        $booking = $this->fm->create(Booking::class);

        $mail = (new \mail\mails\bookingOwner\ConfirmationFactory())->create($booking);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\bookingOwner\FailedFactory())->create($booking);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\bookingOwner\PayoutFactory())->create($booking);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\bookingOwner\RequestFactory())->create($booking);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\bookingOwner\StartFactory())->create($booking);
        $this->checkMail($I, $mail);
    }

    public function testBookingRenter(FunctionalTester $I)
    {
        $booking = $this->fm->create(Booking::class);

        $mail = (new \mail\mails\bookingRenter\ConfirmationFactory())->create($booking);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\bookingRenter\DeclineFactory())->create($booking);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\bookingRenter\FailedFactory())->create($booking);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\bookingRenter\ReceiptFactory())->create($booking);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\bookingRenter\RequestFactory())->create($booking);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\bookingRenter\StartFactory())->create($booking);
        $this->checkMail($I, $mail);
    }

    public function testConversation(FunctionalTester $I)
    {
        $message = $this->fm->create(Message::class);

        $mail = (new \mail\mails\conversation\NewMessageFactory())->create($message);
        $this->checkMail($I, $mail);
    }

    public function testItem(FunctionalTester $I)
    {
        $item = $this->fm->create(Item::class);

        $mail = (new \mail\mails\item\UnfinishedReminderFactory())->create($item);
        $this->checkMail($I, $mail);
    }

    public function testReview(FunctionalTester $I)
    {
        $booking = $this->fm->create(Booking::class);

        $mail = (new \mail\mails\review\PublishFactory())->create($booking, true);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\review\PublishFactory())->create($booking, false);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\review\ReminderFactory())->create($booking, true);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\review\ReminderFactory())->create($booking, false);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\review\RequestFactory())->create($booking, true);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\review\RequestFactory())->create($booking, false);
        $this->checkMail($I, $mail);
    }

    public function testUser(FunctionalTester $I)
    {
        $user = $this->fm->create(User::class);

        $mail = (new \mail\mails\user\ReconfirmFactory())->create($user);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\user\RecoveryFactory())->create($user);
        $this->checkMail($I, $mail);

        $mail = (new \mail\mails\user\WelcomeFactory())->create($user);
        $this->checkMail($I, $mail);
    }

    /**
     * Render a mail.
     *
     * @param Mail $mail Mail to render.
     * @return string HTML output.
     */
    private function renderMail($mail)
    {
        $renderer = new MailRenderer($mail);
        return $renderer->render();
    }

    /**
     * Check if an e-mail is correct.
     *
     * @param functionalTester $I
     * @param Mail $mail Mail to check.
     */
    private function checkMail(FunctionalTester $I, $mail)
    {
        $I->assertTrue(strlen($mail->subject) > 0, 'Has subject');
        $I->assertTrue(strlen($mail->getReceiverName()) > 0, 'Has receiver name');
        $I->assertTrue(strlen($mail->getSenderName()) > 0, 'Has sender name');
        $I->assertTrue(strlen($mail->getReceiverEmail()) > 0, 'Has receiver e-mail address');
        $I->assertTrue(strlen($mail->getSenderEmail()) > 0, 'Has sender e-mail address');
        $this->checkLinks($I, $mail);
    }

    /**
     * Check if the render output contains all required links.
     *
     * @param functionalTester $I
     * @param Mail $mail The mail to check.
     */
    private function checkLinks(FunctionalTester $I, $mail)
    {
        $renderOutput = $this->renderMail($mail);
        $viewInBrowserUrl = UrlFactory::seeInBrowser($mail->getMailId());
        $changeSettingsUrl = UrlFactory::changeSettings();
        $I->assertContains($viewInBrowserUrl, $renderOutput);
        $I->assertContains($changeSettingsUrl, $renderOutput);
    }

}

?>