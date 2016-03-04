<?php
namespace codecept\api\messaging;

use ApiTester;
use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\UserMuffin;
use League\FactoryMuffin\FactoryMuffin;
use notification\models\base\MobileDevices;

/**
 * API test the notification endpoint.
 *
 * Class SearchItemCest
 * @package codecept\api\item
 */
class NotificationCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
        $this->cleanUp();
    }

    private function cleanUp() {
        MobileDevices::deleteAll();
    }

    /**
     * Register a new device.
     *
     * @param $I ApiTester The tester.
     */
    public function testRegister(ApiTester $I)
    {
        // Test register functionality
        $I->wantTo('register a device');

        $device_id = 'test_device_id';
        $token = 'test_token';
        $platform = 'test';

        $I->sendPOST('notifications/register', [
            'device_id' => $device_id,
            'token' => $token,
            'platform' => $platform
        ]);
        $I->seeResponseCodeIs(200);

        $response = $I->grabResponse();
        $I->assertEquals("true", $response);

        // Check the records
        $results = MobileDevices::find()->where([
            'token' => $token
        ]);
        $I->assertEquals(1, $results->count());
        $device = $results->one();
        $I->assertEquals($device_id, $device->device_id);
        $I->assertEquals($platform, $device->platform);

        // I should not be able to register again with the same token
        $device_id .= '_';
        $platform .= '_';

        $I->sendPOST('notifications/register', [
            'device_id' => $device_id,
            'token' => $token,
            'platform' => $platform
        ]);
        $I->seeResponseCodeIs(200);

        $response = $I->grabResponse();
        $I->assertEquals("false", $response);
    }

    /**
     * Subscribing a device.
     *
     * @param $I ApiTester The tester.
     */
    public function testSubscribe(ApiTester $I) {
        // First register
        $I->wantTo('subscribe a device');
        $device_id = 'test_device_id';
        $token = 'test_token';
        $platform = 'test';

        $I->sendPOST('notifications/register', [
            'device_id' => $device_id,
            'token' => $token,
            'platform' => $platform
        ]);
        $I->seeResponseCodeIs(200);

        $I->sendPOST('notifications/subscribe', [
            'device_id' => $device_id
        ]);
        $I->seeResponseCodeIs(200);

        $response = $I->grabResponse();
        $I->assertEquals("true", $response);

        // Check the records
        $results = MobileDevices::find()->where([
            'token' => $token
        ]);
        $I->assertEquals(1, $results->count());
        $device = $results->one();
        $I->assertEquals($device_id, $device->device_id);
        $I->assertEquals($platform, $device->platform);
        $I->assertEquals(true, $device->is_subscribed);
    }

    /**
     * Unsuscribe a device.
     *
     * @param $I ApiTester The tester.
     */
    public function testUnsubscribe(ApiTester $I) {
        // First register
        $I->wantTo('unsubscribe a device');
        $device_id = 'test_device_id';
        $token = 'test_token';
        $platform = 'test';

        $I->sendPOST('notifications/register', [
            'device_id' => $device_id,
            'token' => $token,
            'platform' => $platform
        ]);
        $I->seeResponseCodeIs(200);

        $I->sendPOST('notifications/unsubscribe', [
            'device_id' => $device_id
        ]);
        $I->seeResponseCodeIs(200);

        $response = $I->grabResponse();
        $I->assertEquals("true", $response);

        // Check the records
        $results = MobileDevices::find()->where([
            'token' => $token
        ]);
        $I->assertEquals(1, $results->count());
        $device = $results->one();
        $I->assertEquals($device_id, $device->device_id);
        $I->assertEquals($platform, $device->platform);
        $I->assertEquals(false, $device->is_subscribed);
    }

    /**
     * Set a user.
     *
     * @param $I ApiTester The tester.
     */
    public function testSetUser(ApiTester $I) {
        // First register
        $I->wantTo('link a user to a device');
        $device_id = 'test_device_id';
        $token = 'test_token';
        $platform = 'test';

        $I->sendPOST('notifications/register', [
            'device_id' => $device_id,
            'token' => $token,
            'platform' => $platform
        ]);
        $I->seeResponseCodeIs(200);

        // Create a user
        $fm = (new MuffinHelper())->init();
        $user = $fm->create(UserMuffin::class);

        // And link it to the device
        $accessToken = UserHelper::apiLogin($user)['access-token'];
        $I->sendPOST('notifications/set-user?access-token=' . $accessToken, [
            'device_id' => $device_id,
        ]);
        $I->seeResponseCodeIs(200);

        $results = MobileDevices::find()->where([
            'token' => $token
        ]);
        $I->assertEquals(1, $results->count());
        $device = $results->one();
        $I->assertEquals($user->id, $device->user_id);
    }

    /**
     * Check whether a device is subscribed.
     *
     * @param $I ApiTester The tester.
     */
    public function testIsSubscribed(ApiTester $I) {
        // First register
        $I->wantTo('check whether a device is subscribed');
        $device_id = 'test_device_id';
        $token = 'test_token';
        $platform = 'test';

        $I->sendPOST('notifications/register', [
            'device_id' => $device_id,
            'token' => $token,
            'platform' => $platform
        ]);
        $I->seeResponseCodeIs(200);

        $I->sendPOST('notifications/subscribe', [
            'device_id' => $device_id
        ]);
        $I->seeResponseCodeIs(200);

        $I->sendPOST('notifications/is-subscribed', [
            'device_id' => $device_id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = $I->grabResponse();
        $result = json_decode($response, true);
        $I->assertTrue(array_key_exists('is_subscribed', $result), 'response should contain key is_subscribed');
        $I->assertEquals(true, $result['is_subscribed']);

        $I->sendPOST('notifications/unsubscribe', [
            'device_id' => $device_id
        ]);
        $I->seeResponseCodeIs(200);

        $I->sendPOST('notifications/is-subscribed', [
            'device_id' => $device_id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = $I->grabResponse();
        $result = json_decode($response, true);
        $I->assertTrue(array_key_exists('is_subscribed', $result), 'response should contain key is_subscribed');
        $I->assertEquals(false, $result['is_subscribed']);
    }

}
