<?php
namespace codecept\functional\item;

use codecept\_support\MuffinHelper;
use codecept\muffins\ItemMuffin;
use functionalTester;
use League\FactoryMuffin\FactoryMuffin;

/**
 * functional test for the item module.
 *
 * Class ItemCest
 * @package codecept\functional\item
 */
class ItemViewCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm;

    private function _before() {
        $this->fm = (new MuffinHelper())->init();
    }

    private function checkItemView(functionalTester $I)
    {
        /**
         * @var ItemMuffin $item
         */
        $item = $this->fm->create(ItemMuffin::class);
        $I->amOnPage('/item/'.$item->id);
        $I->see($item->name);
        $I->see($item->description);
        $I->see("Product info");
        $I->see("Request to Book");
        $I->see($item->price_day.",-");
    }
}

?>