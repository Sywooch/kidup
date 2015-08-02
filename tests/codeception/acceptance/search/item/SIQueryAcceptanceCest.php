<?php
use app\modules\search\models\ItemModel;
use app\tests\codeception\_support\FixtureHelper;

/**
 * An acceptance test for the form of the item search.
 *
 * @author kevin91nl
 */
class SIQueryAcceptanceCest {

    /**
     * Initialize the test.
     *
     * @param AcceptanceTester $I
     */
    public function _before(\AcceptanceTester $I) {
        (new FixtureHelper)->fixtures();
    }

    /**
     * Check whether it is possible to search by query.
     *
     * @param FunctionalTester $I
     */
    public function searchByDefault(\AcceptanceTester $I) {
        $I->wantTo('ensure that I can find related items when searching for a particular search term');
        $I->amOnPage('/search/item/index');
        $I->resizeWindow(1024, 500);
        $query = 'strange search term';

        $I->waitForElementVisible('input[name=query]', 5);
        $I->fillField('.search-default input[name=query]', $query);
        $I->wait(2);

        // check the number of corresponding items
        $model = new ItemModel();
        $model->loadParameters([
            'query' => $query
        ]);
        $results = $model->findItems();
        $numItems = $results->count;

        $I->canSeeNumberOfElements('#itemSearch .results-default .item', $numItems);
    }

}