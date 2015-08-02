<?php
namespace app\modules\item\models;

use app\modules\search\models\ItemModel;
use app\tests\codeception\_support\FixtureHelper;
use Yii;
use yii\codeception\TestCase;
use Codeception\Specify;

/**
 * Test for the item search module model.
 *
 * @package app\modules\item\models
 * @author kevin91nl
 */
class SIModelTest extends TestCase
{
    use Specify;

    /**
     * Load the fixtures.
     *
     * @return mixed
     */
    public function fixtures() {
        return (new FixtureHelper)->fixtures();
    }

    /**
     * Test the loaded fixtures.
     */
    public function testFixtures() {
        $this->specify('test fixtures', function () {
            $numItems = count($this->item);
            expect('test whether there is loaded at least one item', $numItems)->greaterThan(0);
        });
    }

    /**
     * Test the search results.
     */
    public function testSearchResults() {
        $this->specify('test search results', function () {
            $model = new ItemModel();
            $results = $model->findItems();
            $numResults = $results->count;
            $numItems = count($this->item);
            expect('test whether all items are found when no parameters are specified', $numResults)->equals($numItems);
        });
    }

}