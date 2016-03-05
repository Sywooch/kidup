<?php
namespace codecept\functional\message;

use codecept\_support\MuffinHelper;
use codecept\muffins\ItemMuffin;
use FunctionalTester;
use item\widgets\ItemCard;
use League\FactoryMuffin\FactoryMuffin;

/**
 * Functional test for the item card widget.
 *
 * Class ItemCardCest
 * @package codecept\functional\widget
 */
class ItemCardCest
{

    /**
     * @var FactoryMuffin
     */
    private $fm;
    private function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }
    /**
     * Test whether the item card display the correct data.
     *
     * @param functionalTester $I
     */
    private function testItemCardDisplay(FunctionalTester $I) {
        $item = $this->fm->create(ItemMuffin::class);
        $card = ItemCard::widget([
            'model' => $item,
            'showDistance' => false
        ]);
        $I->assertContains($item->name, $card);
        $I->assertContains($item->price_day . '', $card);
        $I->assertContains($item->location->city, $card);
    }

}
?>