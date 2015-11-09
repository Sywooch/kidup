<?php
namespace codecept\api\item;

use codecept\_support\MuffinHelper;
use codecept\muffins\Item;
use League\FactoryMuffin\FactoryMuffin;
use ApiTester;
/**
 * API test for viewing an item.
 *
 * Class ViewItemCest
 * @package codecept\api\item
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