<?php

namespace app\tests\codeception\_support;

use app\tests\codeception\fixtures\BookingFixture;
use app\tests\codeception\fixtures\ConversationFixture;
use app\tests\codeception\fixtures\ItemFixture;
use app\tests\codeception\fixtures\ItemHasCategoryFixture;
use app\tests\codeception\fixtures\ItemHasMediaFixture;
use app\tests\codeception\fixtures\LocationFixture;
use app\tests\codeception\fixtures\MediaFixture;
use app\tests\codeception\fixtures\MessageFixture;
use app\tests\codeception\fixtures\PayinFixture;
use app\tests\codeception\fixtures\PayoutFixture;
use app\tests\codeception\fixtures\PayoutMethodFixture;
use app\tests\codeception\fixtures\ProfileFixture;
use app\tests\codeception\fixtures\SettingFixture;
use app\tests\codeception\fixtures\TokenFixture;
use app\tests\codeception\fixtures\UserFixture;
use Codeception\Module;
use Codeception\TestCase;
use yii\test\FixtureTrait;
use yii\test\InitDbFixture;

class FixtureHelper extends Module
{
    /**
     * Redeclare visibility because codeception includes all public methods that do not start with "_"
     * and are not excluded by module settings, in actor class.
     */
    use FixtureTrait {
        loadFixtures as protected;
        fixtures as protected;
        globalFixtures as protected;
        unloadFixtures as protected;
        getFixtures as protected;
        getFixture as protected;
    }
    /**
     * Method called before any suite tests run. Loads User fixture login user
     * to use in acceptance and functional tests.
     * @param array $settings
     */
    public function _beforeSuite($settings = [])
    {
        $this->loadFixtures();
    }
    /**
     * Method is called after all suite tests run
     */
    public function _afterSuite()
    {
        $this->unloadFixtures();
    }

    /**
     * @inheritdoc
     */
    public function globalFixtures()
    {
        return [
            InitDbFixture::className(),
        ];
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
            'payout' => [
                'class'    => PayoutFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/payout.php',
            ],
            'booking' => [
                'class'    => BookingFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/booking.php',
            ],

        ];
    }
}
