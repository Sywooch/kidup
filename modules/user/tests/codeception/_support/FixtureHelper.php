<?php

namespace app\modules\user\tests\codeception\_support;

use app\tests\codeception\fixtures\ConversationFixture;
use app\tests\codeception\fixtures\ItemFixture;
use app\tests\codeception\fixtures\ItemHasCategoryFixture;
use app\tests\codeception\fixtures\ItemHasMediaFixture;
use app\tests\codeception\fixtures\LocationFixture;
use app\tests\codeception\fixtures\MediaFixture;
use app\tests\codeception\fixtures\MessageFixture;
use app\tests\codeception\fixtures\PayinFixture;
use app\tests\codeception\fixtures\PayoutMethodFixture;
use app\tests\codeception\fixtures\ProfileFixture;
use app\tests\codeception\fixtures\SettingFixture;
use app\tests\codeception\fixtures\TokenFixture;
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
            'token' => [
                'class'    => TokenFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/token.php',
            ],
            'profile' => [
                'class'    => ProfileFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/profile.php',
            ],
            'location' => [
                'class'    => LocationFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/location.php',
            ],
            'settings' => [
                'class'    => SettingFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/setting.php',
            ],
            'item' => [
                'class'    => ItemFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/item.php',
            ],
            'conversation' => [
                'class'    => ConversationFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/conversation.php',
            ],
            'media' => [
                'class'    => MediaFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/media.php',
            ],
            'message' => [
                'class'    => MessageFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/message.php',
            ],
            'payin' => [
                'class'    => PayinFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/payin.php',
            ],
            'payout_method' => [
                'class'    => PayoutMethodFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/payout_method.php',
            ],
            'item_has_category' => [
                'class'    => ItemHasCategoryFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/item_has_category.php',
            ],
            'item_has_media' => [
                'class'    => ItemHasMediaFixture::className(),
                'dataFile' => '@app/tests/codeception/fixtures/data/item_has_media.php',
            ],

        ];
    }
}
