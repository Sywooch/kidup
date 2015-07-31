<?php
use app\tests\codeception\_support\FixtureHelper;

/**
 * An acceptance test for the form of the item search.
 *
 * @author kevin91nl
 */
class ModuleBehaviourCest {

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
    public function checkFilterButtonSmallWindow(\AcceptanceTester $I) {
        $I->wantTo('ensure that the filter button is shown on small windows');
        $I->amOnPage('/search/item/index');
        $I->resizeWindow(500, 700);
        $I->canSeeElement('button[name=filter]');
    }

    /**
     * Test whether the filter button is not shown on large windows.
     *
     * @param AcceptanceTester $I
     */
    public function checkFilterButtonLargeWindow(\AcceptanceTester $I) {
        $I->wantTo('ensure that the filter button is not shown on large windows');
        $I->amOnPage('/search/item/index');
        $I->resizeWindow(1024, 700);
        $I->dontSeeElement('button[name=filter]');
    }

    /**
     * Test whether the sidebar is not shown on small windows.
     *
     * @param AcceptanceTester $I
     */
    public function checkSearchSidebarSmallWindow(\AcceptanceTester $I) {
        $I->wantTo('ensure that the search sidebar is not shown on small windows');
        $I->amOnPage('/search/item/index');
        $I->resizeWindow(500, 700);
        $I->dontSeeElement('.search-sidebar');
    }

    /**
     * Test whether the sidebar is shown on large windows.
     *
     * @param AcceptanceTester $I
     */
    public function checkSearchSidebarLargeWindow(\AcceptanceTester $I) {
        $I->wantTo('ensure that the search sidebar is shown on large windows');
        $I->amOnPage('/search/item/index');
        $I->resizeWindow(1024, 700);
        $I->canSeeElement('.search-sidebar');
    }

}