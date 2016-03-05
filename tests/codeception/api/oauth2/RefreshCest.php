<?php
namespace oauth2;
use ApiTester;
use codecept\_support\MuffinHelper;
use League\FactoryMuffin\FactoryMuffin;

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