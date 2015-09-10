<?php
namespace app\tests\codeception\functional\message;

use app\modules\item\models\Item;
use app\modules\item\widgets\ItemCard;
use FunctionalTester;

/**
 * Functional test for the item card widget.
 *
 * Class ItemCardCest
 * @package app\tests\codeception\functional\widget
 */
class ItemCardCest
{

    /**
     * Test whether the item card display the correct data.
     *
     * @param functionalTester $I
     */
    public function testItemCardDisplay(FunctionalTester $I) {
        $item = Item::find()
            ->where([
                'name' => 'Test Item'
            ])
            ->one();
        $card = ItemCard::widget([
            'model' => $item,
            'showDistance' => false
        ]);
        $I->assertContains($item->name, $card);
        $I->assertContains($item->price_week, $card);
        $I->assertContains('Aarhus', $card);
    }

}
?>