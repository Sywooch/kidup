<?php
use app\tests\codeception\_support\FixtureHelper;

/**
 * An acceptance test for the form of the item search.
 *
 * @author kevin91nl
 */
class SIAcceptanceCest {

    /**
     * Initialize the test.
     *
     * @param AcceptanceTester $I
     */
    public function _before(\AcceptanceTester $I) {
        (new FixtureHelper)->fixtures();
    }

    /**
     * Test whether the filter button is shown on small windows.
     *
     * @param AcceptanceTester $I
     */
    public function checkFilterButton(\AcceptanceTester $I) {
        $I->wantTo('ensure that the filter button is shown correctly');
        $I->amOnPage('/search/item/index');
        $I->resizeWindow(500, 700);
        $I->canSeeElement('#itemSearch button[name=filter]');
        $I->resizeWindow(1024, 700);
        $I->dontSeeElement('#itemSearch button[name=filter]');
    }

    /**
     * Test whether the sidebar is not shown on small windows.
     *
     * @param AcceptanceTester $I
     */
    public function checkSidebar(\AcceptanceTester $I) {
        $I->wantTo('ensure that the search sidebar is shown correctly');
        $I->amOnPage('/search/item/index');
        $I->resizeWindow(500, 700);
        $I->dontSeeElement('#itemSearch .search-default');
        $I->resizeWindow(1024, 700);
        $I->canSeeElement('#itemSearch .search-default');
    }

    /**
     * Check whether a modal opens when clicking the filter button.
     *
     * @param FunctionalTester $I
     */
    public function checkModalOpens(\AcceptanceTester $I) {
        $I->wantTo('ensure that the modal opens when clicking the filter button');
        $I->amOnPage('/search/item/index');
        $I->resizeWindow(500, 700);
        $I->dontSeeElement('.filter-modal');
        $I->click('#itemSearch button[name=filter]');
        $I->canSeeElement('.filter-modal');
    }

}