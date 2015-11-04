<?php

namespace tests\api\item;
use League\FactoryMuffin\FactoryMuffin;
use ApiTester;
use tests\_support\MuffinHelper;
use tests\muffins\Item;

/**
 * API test for the item search.
 *
 * Class SearchCest
 * @package tests\api\item
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
        $I->sendPOST('items/search', [
            'page' => 0
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), true);

        $I->assertTrue(array_key_exists('results', $response));
        $I->assertEquals(count($response['results']), $itemsPerPage);

        $I->assertTrue(array_key_exists('num_items', $response));
        $I->assertEquals($response['num_items'], $n);

        $I->assertTrue(array_key_exists('num_pages', $response));
        $I->assertEquals($response['num_pages'], ceil($n / $itemsPerPage));
    }

}