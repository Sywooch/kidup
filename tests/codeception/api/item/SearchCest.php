<?php
namespace tests\api\item;

use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\muffins\Item;
use League\FactoryMuffin\FactoryMuffin;

/**
 * API test for the item search.
 *
 * Class SearchItemCest
 * @package app\tests\codeception\api\item
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
     * Generate $n items.
     *
     * @param $n int Number of items to generate (default: 24).
     * @return \item\models\Item[] List of items.
     */
    private function generateItems($n = 24) {
        $items = [];
        for ($i = 0; $i < $n; $i++) {
            $items[] = $this->fm->create(Item::className());
        }
        return $items;
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
        $this->generateItems($n);

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