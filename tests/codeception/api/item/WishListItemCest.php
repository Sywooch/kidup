<?php

namespace codecept\api\item;

use ApiTester;
use codecept\_support\MuffinHelper;
use codecept\muffins\ItemMuffin;
use League\FactoryMuffin\FactoryMuffin;
use Codeception\Util\Debug;
/**
 * API test for viewing related items of an item.
 *
 * Class RelatedItemCest
 * @package codecept\api\item
 */
class WishListItemCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }

}