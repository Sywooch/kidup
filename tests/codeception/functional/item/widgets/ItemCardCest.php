<?php
namespace app\tests\codeception\functional\message;

use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\muffins\Item;
use FunctionalTester;
use item\widgets\ItemCard;
use League\FactoryMuffin\FactoryMuffin;

/**
 * Functional test for the item card widget.
 *
 * Class ItemCardCest
 * @package app\tests\codeception\functional\widget
 */
class ItemCardCest
{

    /**
     * @var FactoryMuffin
     */
    public $fm;
    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }
    /**
     * Test whether the item card display the correct data.
     *
     * @param functionalTester $I
     */
    public function testItemCardDisplay(FunctionalTester $I) {
        $item = $this->fm->create(Item::class);
        $card = ItemCard::widget([
            'model' => $item,
            'showDistance' => false
        ]);
        $I->assertContains($item->name, $card);
        $I->assertContains($item->price_week . '', $card);
        $I->assertContains($item->location->city, $card);
    }

}
?>