<?php
namespace app\tests\codeception\functional\message;

use app\modules\item\models\Location;
use FunctionalTester;

/**
 * Functional test for the Google Maps API.
 *
 * Class GoogleMapsAPI
 * @package app\tests\codeception\functional\vendor
 */
class GoogleMapsAPI
{

    /**
     * Test whether the Google Maps API gives back the data as expected.
     *
     * @param functionalTester $I
     */
    public function testGoogleMapsAPI(FunctionalTester $I) {
        $location = Location::getByAddress('Aarhus Denmark');
        // up to some rounding precision, the longitude and latitude should be correct
        $I->assertEquals(10, (int)$location['longitude']);
        $I->assertEquals(56, (int)$location['latitude']);
    }

}
?>