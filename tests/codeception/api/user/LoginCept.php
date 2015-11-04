<?php 
$I = new ApiTester($scenario);
$I->wantTo('create a user via API');
$I->sendGET('items');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(['pageCount' => 1]);