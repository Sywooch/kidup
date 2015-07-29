<?php

namespace app\tests\codeception\_support;

use app\tests\codeception\fixtures\BookingFixture;
use app\tests\codeception\fixtures\ConversationFixture;
use app\tests\codeception\fixtures\ItemHasCategoryFixture;
use app\tests\codeception\fixtures\LocationFixture;
use app\tests\codeception\fixtures\MediaFixture;
use app\tests\codeception\fixtures\MessageFixture;
use app\tests\codeception\fixtures\PayinFixture;
use app\tests\codeception\fixtures\PayoutMethodFixture;
use app\tests\codeception\fixtures\ProfileFixture;
use app\tests\codeception\fixtures\SettingFixture;
use app\tests\codeception\fixtures\TokenFixture;
use app\tests\codeception\fixtures\ItemFixture;
use app\tests\codeception\fixtures\ItemHasMediaFixture;
use Codeception\Module;
use Codeception\TestCase;
use app\tests\codeception\fixtures\UserFixture;

use yii\test\FixtureTrait;

class FixtureHelper extends Module
{
    use FixtureTrait;

    /**
     * @var array
     */
    public static $excludeActions = ['loadFixtures', 'unloadFixtures', 'getFixtures', 'globalFixtures', 'fixtures'];

    /**
     * @param TestCase $testcase
     */
    public function _before(TestCase $testcase)
    {
        $this->unloadFixtures();
        $this->loadFixtures();
        parent::_before($testcase);
    }

    /**
     * @param TestCase $testcase
     */
    public function _after(TestCase $testcase)
    {
        $this->unloadFixtures();
        parent::_after($testcase);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'user' => [
                'class'    => UserFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/user.php',
            ],
            'profile' => [
                'class'    => ProfileFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/profile.php',
            ],
            'token' => [
                'class'    => TokenFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/token.php',
            ],
            'conversation' => [
                'class'    => ConversationFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/conversation.php',
            ],
            'setting' => [
                'class'    => SettingFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/setting.php',
            ],
            'payoutMethod' => [
                'class'    => PayoutMethodFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/payout_method.php',
            ],
            'location' => [
                'class'    => LocationFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/location.php',
            ],
            'item' => [
                'class'    => ItemFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/item.php',
            ],
            'itemHasCategory' => [
                'class'    => ItemHasCategoryFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/item_has_category.php',
            ],
            'media' => [
                'class'    => MediaFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/media.php',
            ],
            'itemHasMedia' => [
                'class'    => ItemHasMediaFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/item_has_media.php',
            ],
            'message' => [
                'class'    => MessageFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/message.php',
            ],
            'payin' => [
                'class'    => PayinFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/payin.php',
            ],
            'booking' => [
                'class'    => BookingFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/booking.php',
            ],

        ];
    }
}
