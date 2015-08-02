<?php
use app\modules\item\models\Item;
use app\tests\codeception\_support\FixtureHelper;
use tests\codeception\_pages\ItemSearchPage;

/**
 * A functional test for the form of the item search.
 *
 * @author kevin91nl
 */
class FormCest {

    /**
     * Initialize the test.
     *
     * @param FunctionalTester $I
     */
    public function _before(\FunctionalTester $I) {
        // load the fixtures
        (new FixtureHelper)->fixtures();
    }

    /**
     * Check whether the form exists.
     *
     * @param FunctionalTester $I
     */
    public function checkFormExists(\FunctionalTester $I) {
        $I->wantTo('ensure that the item search form exists');
        ItemSearchPage::openBy($I);
        $I->canSeeElement('#itemSearch form[name=itemSearch]');
    }

    /**
     * Check whether the query input exists.
     *
     * @param FunctionalTester $I
     */
    public function checkQueryInputExists(\FunctionalTester $I) {
        $I->wantTo('ensure that the search term input field exists');
        ItemSearchPage::openBy($I);
        $I->canSeeElement('#itemSearch input[name=query]');
    }

    /**
     * Check whether on default all items are shown (on the default view).
     *
     * @param FunctionalTester $I
     */
    public function checkNumberOfItemsDefault(\FunctionalTester $I) {
        $I->wantTo('ensure that all the items are shown on the default view');
        ItemSearchPage::openBy($I);
        $numItems = Item::find()->count();
        $I->canSeeNumberOfElements('#itemSearch .results-default .item', $numItems);
    }

    /**
     * Check whether on default all items are shown (on the modal view).
     *
     * @param FunctionalTester $I
     */
    public function checkNumberOfItemsModal(\FunctionalTester $I) {
        $I->wantTo('ensure that all the items are shown on the modal view');
        ItemSearchPage::openBy($I);
        $numItems = Item::find()->count();
        $I->canSeeNumberOfElements('#itemSearch .results-modal .item', $numItems);
    }

}