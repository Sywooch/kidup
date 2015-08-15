<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

/**
 * Represents item search page
 *
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class ItemSearchPage extends BasePage
{
    public $route = 'search';

    public function search($term) {
        $this->actor->fillField('query', $term);
    }

}
