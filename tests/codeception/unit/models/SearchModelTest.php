<?php
namespace app\modules\item\models;
use Yii;
use yii\codeception\TestCase;
use Codeception\Specify;

// @todo remove
class SearchModelTest extends TestCase
{
    use Specify;

    /**
     * Test whether the search parameters are loaded correctly when passed only a query parameter.
     */
    public function testSimpleParameters()
    {
        $query = 'Test search query';
        $model = new Search([
            'query' => $query
        ]);
        $this->specify('test simple search parameters', function () use ($model, $query) {
            expect('test whether the query variable is loaded correctly if the query parameter was set', $model->query)->equals($query);
        });
    }

    /**
     * Test whether the search parameters are loaded correctly when passed a q parameter.
     */
    public function testAdvancedParameters()
    {
        $model = new Search([
            'q' => 'location|Breda|distance|2|query|test|priceMin|140|priceMax|790|age|1,2|categories|12|'
        ]);
        $this->specify('test advanced search parameters', function () use ($model) {
            expect('test whether the location parameter is loaded correctly', $model->location)->equals('Breda');
            expect('test whether the distance parameter is loaded correctly', $model->distance)->equals('2');
            expect('test whether the query parameter is loaded correctly', $model->query)->equals('test');
            expect('test whether the minimum price parameter is loaded correctly', $model->priceMin)->equals('140');
            expect('test whether the maximum price is loaded correctly', $model->priceMax)->equals('790');
            expect('test whether the age parameter contains "1"', $model->age)->contains(1);
            expect('test whether the age parameter contains "2"', $model->age)->contains(2);
            expect('test whether the categories parameter contains "12"', $model->categories)->contains(12);
        });
    }

    /**
     * Test whether the distance calculations are correct, i.e. the value of the slider
     * corresponds to the right value.
     */
    public function testDistanceCalculations() {
        $model = new Search([
            'q' => 'location|Breda|distance|2|query|test|priceMin|140|priceMax|790|age|1,2|categories|12|'
        ]);
        $pairs = [
            [0, 0],
            [0.5, 500],
            [1, 1000],
            [1.5, 5000],
            [2, 10000],
            [2.5, 50000],
            [2.4, 40000],
            [3, -1],
        ];
        $this->specify('test index-to-distance calculations', function() use ($model, $pairs) {
            $newPairs = $pairs;
            $newPairs[] = [4, -1];
            foreach ($newPairs as $pair) {
                list($index, $distance) = $pair;
                expect('index ' . $index .' should be converted to distance ' . $distance, $model->calculateDistance($index))->equals($distance);
            }
        });
        $this->specify('test distance-to-index calculations', function() use ($model, $pairs) {
            foreach ($pairs as $pair) {
                list($index, $distance) = $pair;
                expect('distance ' . $distance .' should be converted to index ' . $index, $model->calculateDistanceIndex($distance))->equals($index);
            }
        });
        $this->specify('test border calculations', function() use ($model) {
            expect('distance -1 should be converted to index 3', $model->calculateDistanceIndex(-1))->equals(3);
        });
    }

}