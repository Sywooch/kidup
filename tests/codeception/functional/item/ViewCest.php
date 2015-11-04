<?php
namespace tests\functional\item;

use tests\muffins\Item;
use functionalTester;
use League\FactoryMuffin\FactoryMuffin;
use tests\_support\MuffinHelper;
/**
 * functional test for the item module.
 *
 * Class ItemCest
 * @package tests\functional\item
 */
class ItemViewCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm;

    public function _before() {
        $this->fm = (new MuffinHelper())->init();
    }

    public function checkItemView(functionalTester $I)
    {
        /**
         * @var Item $item
         */
        $item = $this->fm->create(Item::class);
        $I->amOnPage('/item/'.$item->id);
        $I->see($item->name);
        $I->see($item->description);
        $I->see("Product info");
        $I->see("Request to Book");
        $I->see($item->price_day.",-");
    }
}

?>