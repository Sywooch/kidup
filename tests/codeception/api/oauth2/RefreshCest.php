<?php
namespace oauth2;
use \ApiTester;
use League\FactoryMuffin\FactoryMuffin;
use app\tests\codeception\_support\MuffinHelper;

class RefreshCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
    }
}