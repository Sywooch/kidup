<?php
namespace app\tests\codeception\acceptance\search;

use AcceptanceTester;
use app\modules\item\models\Item;
use app\modules\search\models\SearchModel;
use app\tests\codeception\_support\FixtureHelper;

/**
 * An acceptance test for the item search.
 *
 * Class SearchCest
 * @package app\tests\codeception\acceptance\search
 */
class SearchCest {

    /**
     * Initialize the test.
     *
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I) {
        (new FixtureHelper)->fixtures();
    }

    /**
     * Test whether the filter button is shown correctly.
     *
     * @param AcceptanceTester $I
     */
    public function checkFilterButton(AcceptanceTester $I) {
        $I->wantTo('ensure that the filter button is shown correctly');
        $I->amOnPage('/search/search');
        $I->resizeWindow(500, 700);
        $I->canSeeElement('button[id=filter-button]');
        $I->resizeWindow(1024, 700);
        $I->dontSeeElement('button[id=filter-button]');
    }

    /**
     * Test whether the sidebar is not shown on small windows.
     *
     * @param AcceptanceTester $I
     */
    public function checkSidebar(AcceptanceTester $I) {
        $I->wantTo('ensure that the search sidebar is shown correctly');
        $I->amOnPage('/search/search');
        $I->resizeWindow(500, 700);
        $I->dontSeeElement('#search-sidebar > div');
        $I->resizeWindow(1024, 700);
        $I->canSeeElement('#search-sidebar > div');
    }

    /**
     * Check whether a modal opens when clicking the filter button.
     *
     * @param FunctionalTester $I
     */
    public function checkModalOpens(AcceptanceTester $I) {
        $I->wantTo('ensure that the modal opens when clicking the filter button');
        $I->amOnPage('/search/search');
        $I->resizeWindow(500, 700);
        $I->dontSeeElement('.modal');
        $I->click('button[id=filter-button]');
        $I->wait(2);
        $I->canSeeElement('.modal');
    }

    /**
     * Check whether it is possible to search by query.
     *
     * @param FunctionalTester $I
     */
    public function searchByDefault(AcceptanceTester $I) {
        $I->wantTo('ensure that I can find related items when searching for a particular search term');
        $I->amOnPage('/search/search');
        $I->resizeWindow(1024, 500);
        $query = 'strange search term';

        $I->waitForElementVisible('input[name=query]', 5);
        $I->fillField('input[name=query]', $query);
        $I->wait(2);

        // check the number of corresponding items
        $model = new SearchModel();
        $model->loadParameters([
            'query' => $query
        ]);
        $results = $model->findItems();
        $numItems = $results->count;

        $I->canSeeNumberOfElements('.searchResults .item', $numItems);
    }

}