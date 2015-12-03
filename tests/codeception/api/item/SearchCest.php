<?php

namespace codecept\api\item;
use League\FactoryMuffin\FactoryMuffin;
use ApiTester;
use codecept\_support\MuffinHelper;
use codecept\muffins\Item;
/**
 * API test for the item search.
 *
 * Class SearchItemCest
 * @package codecept\api\item
 */
class SearchCest
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
     * Perform a simple search.
     *
     * @param $I ApiTester The tester.
     */
    public function checkSimpleSearch(ApiTester $I)
    {
        $n = 25;
        $itemsPerPage = 12;

        // generate the items
        $this->fm->seed($n, Item::className());

        $I->wantTo("perform a simple search with {$n} items");
        $I->sendGET('items/search');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), true);

        $I->assertTrue(array_key_exists('items', $response));
//        $I->assertEquals(count($response['items']), $itemsPerPage);

//        $I->assertTrue(array_key_exists('num_items', $response));
//        $I->assertEquals($response['_meta'], $n);
//
//        $I->assertTrue(array_key_exists('num_pages', $response));
//        $I->assertEquals($response['num_pages'], ceil($n / $itemsPerPage));
    }

}