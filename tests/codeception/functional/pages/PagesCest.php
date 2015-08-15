<?php
namespace app\tests\codeception\functional\pages;

use app\components\Pages;
use FunctionalTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Functional test for the pages module.
 *
 * Class PagesCest
 * @package app\tests\codeception\functional\pages
 */
class PagesCest
{

    /**
     * Initialize the test.
     *
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        (new FixtureHelper)->fixtures();
    }

    public function checkStaticPages(FunctionalTester $I){
        $I->wantTo('check that the about page works');

        // loop trough all known static pages
        $pages = (new Pages())->getPages();
        foreach ($pages as $page) {
            $I->amOnPage('/p/'.$page);
            $I->seeElement('.container .card');
        }
    }

}

?>