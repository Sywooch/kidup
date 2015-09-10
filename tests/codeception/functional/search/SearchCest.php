<?php
namespace app\tests\codeception\functional\search;

use FunctionalTester;
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

    /**
     * Check whether the search results page exists.
     *
     * @param FunctionalTester $I
     */
    public function checkSearchResultsPageExists(FunctionalTester $I) {
        $I->wantTo('ensure that the search result page exists');
        $I->amOnPage('search-results?q=');
        $I->canSeeElement('#results');
    }

    /**
     * Check whether it is possible to search by query.
     *
     * @param FunctionalTester $I
     */
    public function checkSearchByQuery(FunctionalTester $I) {
        $I->wantTo('ensure that it is possible to search by query');
        $I->amOnPage('/search-results?q=query|Test');
        $this->notEmpty($I);
        $I->amOnPage('/search-results?q=query|123123');
        $this->isEmpty($I);
    }

    /**
     * Check whether it is possible to search by category.
     *
     * @param FunctionalTester $I
     */
    public function checkSearchByCategory(FunctionalTester $I) {
        $I->wantTo('ensure that it is possible to search by category');
        $I->amOnPage('/search-results?q=query|Test|categories|1');
        $this->isEmpty($I);
        $I->amOnPage('/search-results?q=query|Test|categories|2');
        $this->notEmpty($I);
    }

    /**
     * Check whether it is possible to search by price.
     *
     * @param FunctionalTester $I
     */
    public function checkSearchByPrice(FunctionalTester $I) {
        $I->wantTo('ensure that it is possible to search by price');
        $I->amOnPage('/search-results?q=query|Test|price|0,499|priceUnit|week');
        $this->notEmpty($I);
        $I->amOnPage('/search-results?q=query|Test|price|499,499|priceUnit|week');
        $this->isEmpty($I);
        $I->amOnPage('/search-results?q=query|Test|price|245,255|priceUnit|month');
        $this->notEmpty($I);
        $I->amOnPage('/search-results?q=query|Test|price|245,255|priceUnit|day');
        $this->isEmpty($I);
    }

    /**
     * Check whether it is possible to search by location.
     *
     * @param FunctionalTester $I
     */
    public function checkSearchByLocation(FunctionalTester $I) {
        $I->wantTo('ensure that it is possible to search by location');
        $I->amOnPage('/search-results?q=query|Test|location|Breda Netherlands');
        $I->canSee('622 km');
        $I->dontSee('Aarhus');
        $I->amOnPage('/search-results?q=query|Test|location|Aarhus Denmark');
        $I->canSee('1 km');
    }

    /**
     * Check whether it contains results.
     *
     * @param FunctionalTester $I
     */
    private function notEmpty($I) {
        $I->dontSee('No products were found.');
    }

    /**
     * Check whether it contains no results.
     *
     * @param FunctionalTester $I
     */
    private function isEmpty($I) {
        $I->see('No products were found.');
    }

}