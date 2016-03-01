<?php
namespace codecept\unit\item\location;

use FunctionalTester;
use Codeception\Specify;
use item\models\Location;
use yii\codeception\TestCase;

/**
 * Functional test for the Google Maps API.
 *
 * Class GoogleMapsAPI
 * @package codecept\functional\vendor
 */
class GoogleMapsAPI extends TestCase
{

    use Specify;

    /**
     * Test whether the Google Maps API gives back the data as expected.
     */
    public function testGoogleMapsAPI() {
        $this->specify('test that google maps gives right coordinated for aarhus', function () {
            $location = Location::addressToLngLat('Aarhus Denmark');
            // up to some rounding precision, the longitude and latitude should be correct
            expect('longitude to be 10', 10)->equals((int)$location['longitude']);
            expect('longitude to be 10', 56)->equals((int)$location['latitude']);
        });
    }
}
?>