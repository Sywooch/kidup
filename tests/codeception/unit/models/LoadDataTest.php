<?php

namespace tests\codeception\unit\models;

use app\models\base\User;
use app\tests\codeception\_support\FixtureHelper;
use app\tests\codeception\fixtures\UserFixture;
use app\tests\codeception\fixtures\ProfileFixture;
use app\tests\codeception\fixtures\ItemFixture;
use Yii;
use \Codeception\Specify;

class LoadDataTest extends \yii\codeception\DbTestCase
{
    use Specify;

    public function fixtures()
    {
        return (new FixtureHelper)->fixtures();
    }

    public function testDataLoadedCorrectly()
    {
        $this->assertEquals(6, User::find()->count());
    }
}
