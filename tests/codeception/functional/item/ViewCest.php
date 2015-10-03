<?php
namespace app\tests\codeception\functional\item;

use app\tests\codeception\muffins\Item;
use functionalTester;
use League\FactoryMuffin\FactoryMuffin;
use app\tests\codeception\_support\MuffinHelper;
/**
 * functional test for the item module.
 *
 * Class ItemCest
 * @package app\tests\codeception\functional\item
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
        $I->see("DKK ".$item->price_week);
    }
}

?>