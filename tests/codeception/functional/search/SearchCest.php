<?php
namespace app\tests\codeception\functional\search;

use FunctionalTester;
use app\modules\item\models\Item;
use app\tests\codeception\_support\FixtureHelper;
use tests\codeception\_pages\ItemSearchPage;

/**
 * A functional test for the item search.
 *
 * @author kevin91nl
 */
class SearchCest
{

    /**
     * Initialize the test.
     *
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        // load the fixtures
        (new FixtureHelper)->fixtures();
    }

    /**
     * Check whether the form exists.
     *
     * @param FunctionalTester $I
     */
    public function checkFormExists(FunctionalTester $I)
    {
        $I->wantTo('ensure that the search term input field exists');
        ItemSearchPage::openBy($I);
        $I->canSeeElement('#refineQuery > div > input');
        $I->canSeeElement('#price-slider');
    }


}