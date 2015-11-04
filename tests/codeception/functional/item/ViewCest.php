<?php
namespace codecept\functional\item;

use codecept\muffins\Item;
use functionalTester;
use League\FactoryMuffin\FactoryMuffin;
use codecept\_support\MuffinHelper;
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