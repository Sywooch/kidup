<?php
use codecept\_support\MuffinHelper;
use League\FactoryMuffin\FactoryMuffin;

/**
 * functional test for the login.
 *
 * Class RegisterCest
 * @package codecept\api\user
 */
class ReviewCest
{
    protected $user;
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }
}

?>
