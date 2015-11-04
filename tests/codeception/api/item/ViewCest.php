<?php
namespace tests\api\item;

use tests\_support\MuffinHelper;
use tests\muffins\Item;
use League\FactoryMuffin\FactoryMuffin;
use ApiTester;
/**
 * API test for viewing an item.
 *
 * Class ViewCest
 * @package tests\api\item
 */
class ViewCest
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
     * Try to view an item that is not available.
     *
     * @param $I ApiTester The tester.
     */
    public function checkViewNotAvailable(ApiTester $I)
    {
        $I->wantTo("try to view an item that is not available");

        // create an item that is not available
        $item = $this->fm->create(Item::className());
        $item->is_available = false;
        $item->save();

        $I->sendGET('items/' . $item->id);

        $I->seeResponseCodeIs(404);
    }

    /**
     * View an item.
     *
     * @param $I ApiTester The tester.
     */
    public function checkView(ApiTester $I)
    {
        $I->wantTo("view an item");

        // create an item that is available
        $item = $this->fm->create(Item::className());
        $item->is_available = true;
        $item->save();

        $I->sendGET('items/' . $item->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['id' => $item->id]);
    }

}