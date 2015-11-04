<?php

namespace tests\api\item;

use tests\_support\MuffinHelper;
use tests\muffins\Item;
use League\FactoryMuffin\FactoryMuffin;
use ApiTester;

/**
 * API test for viewing related items of an item.
 *
 * Class RelatedItemCest
 * @package tests\api\item
 */
class RelatedItemCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();

        // remove all items
        \item\models\Item::deleteAll();
    }

    /**
     * View related items.
     *
     * @param $I ApiTester The tester.
     */
    public function checkRelatedItems(ApiTester $I)
    {
        $I->wantTo("view related items");

        // create an item that is available
        $item1 = $this->fm->create(Item::className());
        $item1->is_available = true;
        $item1->save();

        // and another 'related' one (since there are only two)
        $item2 = $this->fm->create(Item::className());
        $item2->is_available = true;
        $item2->save();

        $I->sendGET('items/related?item_id=' . $item1->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), true);

        $I->assertTrue(array_key_exists('related_items', $response));
        $relatedItems = $response['related_items'];
        $I->assertEquals(1, count($relatedItems));
        $firstRelatedItem = reset($relatedItems);
        $I->assertEquals($item2->id, $firstRelatedItem['id']);
    }

}