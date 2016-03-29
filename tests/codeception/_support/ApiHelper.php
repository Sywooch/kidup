<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class ApiHelper extends \Codeception\Module
{
    /**
     * Does certain sanity checks and returns the parsed json
     * @param \ApiTester $I
     * @param int $status
     * @return array response in array format
     */
    public static function checkJsonResponse(\ApiTester $I, $status = 200){
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs($status);
        return json_decode($I->grabResponse(), true);
    }
}
