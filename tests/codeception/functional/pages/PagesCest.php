<?php
namespace codecept\functional\pages;

use FunctionalTester;
use pages\components\Pages;

/**
 * Functional test for the pages module.
 *
 * Class PagesCest
 * @package codecept\functional\pages
 */
class PagesCest
{
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