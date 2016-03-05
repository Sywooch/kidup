<?php
namespace codecept\functional\notification;

use app\helpers\Event;
use booking\models\booking\Booking;
use booking\models\payin\Payin;
use codecept\_support\MuffinHelper;
use codecept\muffins\BookingMuffin;
use codecept\muffins\UserMuffin;
use functionalTester;
use League\FactoryMuffin\FactoryMuffin;
use notification\models\base\MobileDevices;
use user\models\User;


/**
 * API test the notification endpoint.
 *
 * Class SearchItemCest
 * @package codecept\api\item
 */
class NotificationDistributionCest
{

    /**
     * @var FactoryMuffin
     */
    protected $fm = null;
    /**
     * @var FunctionalTester
     */
    protected $I;

    /*
    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
        MobileDevices::deleteAll();
    }

    public function checkUnreadCount(FunctionalTester $I){
        $this->I= $I;
        $user = $this->fm->create(UserMuffin::class);
        $c1 = $this->countNumberOfEmails();
        Event::trigger($user, User::EVENT_USER_CREATE_DONE);
        $this->I->assertEquals($c1+1, $this->countNumberOfEmails());

        $this->checkNewEmail(function() use ($user){
            Event::trigger($user, User::EVENT_USER_CREATE_DONE);
        });

        $this->checkNewEmail(function() use ($user){
            Event::trigger($user, User::EVENT_USER_REQUEST_RECOVERY);
        });

        $booking = $this->fm->create(BookingMuffin::className());
        $this->checkNewEmail(function() use ($booking){
            Event::trigger($booking->payin, Payin::EVENT_PAYIN_CONFIRMED);
        });
    }

    private function countNumberOfEmails(){
        return count(glob(\Yii::$aliases['@runtime'].'/mail/*.eml'));
    }

    private function checkNewEmail($callback){
        $c1 = $this->countNumberOfEmails();
        $callback();
        $this->I->assertEquals($c1+1, $this->countNumberOfEmails());
    }*/
}

