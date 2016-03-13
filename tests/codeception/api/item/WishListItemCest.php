<?php

namespace codecept\api\item;

use ApiTester;
use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\ItemMuffin;
use codecept\muffins\UserMuffin;
use codecept\muffins\WishListItemMuffin;
use item\models\wishListItem\WishListItem;
use League\FactoryMuffin\FactoryMuffin;
use Codeception\Util\Debug;

/**
 * API test for viewing related items of an item.
 *
 * Class RelatedItemCest
 * @package codecept\api\item
 */
class WishListItemCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;
    private $user;
    private $accessToken;
    private $item;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
        $this->user = $this->fm->create(UserMuffin::class);
        $this->item = $this->fm->create(ItemMuffin::class);
        $this->accessToken = UserHelper::apiLogin($this->user)['access-token'];
    }


    public function testViewAll(ApiTester $I)
    {
        WishListItem::deleteAll();
        $w1 = $this->fm->create(WishListItemMuffin::class, ['user_id' => $this->user->id]);
        $w2 = $this->fm->create(WishListItemMuffin::class, ['user_id' => $this->user->id]);
        $I->sendGET('wish-list-items', array_merge([
            'access-token' => $this->accessToken,
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['id' => $w1->id]);
        $I->seeResponseContainsJson(['id' => $w2->id]);
    }


    public function testCreate(ApiTester $I)
    {
        $I->sendPOST('wish-list-items?access-token=' . $this->accessToken, array_merge([
            'item_id' => $this->item->id,
        ]));

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['item_id' => $this->item->id]);
        $resp = json_decode($I->grabResponse(), true);
        $w = WishListItem::findOne($resp['id']);
        $I->assertEquals($w->user_id, $this->user->id);
    }

}