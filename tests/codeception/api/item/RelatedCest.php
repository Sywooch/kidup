<?php

namespace codecept\api\item;

use ApiTester;
use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\ItemMuffin;
use codecept\muffins\UserMuffin;
use Codeception\Module\ApiHelper;
use League\FactoryMuffin\FactoryMuffin;
use Codeception\Util\Debug;
/**
 * API test for viewing related items of an item.
 *
 * Class RelatedItemCest
 * @package codecept\api\item
 */
class RelatedCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();

        // remove all items
        \item\models\item\Item::deleteAll();
    }

    /**
     * View related items.
     *
     * @param $I ApiTester The tester.
     */
    public function checkRelatedItems(ApiTester $I)
    {
        $I->wantTo("view related items");

        // Login (such that we are allowed to create and update the item)
        $owner = $this->fm->create(UserMuffin::class);
        UserHelper::login($owner);

        // create an item that is available
        $item1 = $this->fm->create(ItemMuffin::className(), [
            'owner_id' => $owner->id,
            'is_available' => 1
        ]);

        // and another 'related' one (since there are only two)
        $item2 = $this->fm->create(ItemMuffin::className(),[
            'owner_id' => $owner->id,
            'is_available' => 1
        ]);

        $I->sendGET('items/related?item_id=' . $item1->id);
        $response = ApiHelper::checkJsonResponse($I);;

        $I->assertTrue(array_key_exists('related_items', $response));
        $relatedItems = $response['related_items'];
        $I->assertEquals(1, count($relatedItems));
        $firstRelatedItem = reset($relatedItems);
        $I->assertEquals($item2->id, $firstRelatedItem['id']);
    }

}