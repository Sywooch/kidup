<?php
namespace tests\functional\pages;

use FunctionalTester;
use pages\helpers\Pages;

/**
 * Functional test for the pages module.
 *
 * Class PagesCest
 * @package tests\functional\pages
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