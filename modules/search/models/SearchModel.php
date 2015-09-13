<?php
namespace app\modules\search\models;

use app\components\Cache;
use app\modules\item\models\Category;
use app\modules\item\models\Item;
use app\modules\item\models\Location;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\Json;

/**
 * The item model of the search module is used for handling data related to searching items.
 *
 * Class ItemModel
 * @package app\modules\search\models
 * @author kevin91nl
 */
class SearchModel extends Model
{

    public $query = null;
    public $location = null;
    public $longitude = null;
    public $latitude = null;
    public $category_id = null;
    public $price = null;
    public $priceMin = 0;
    public $priceMax = 499;
    public $priceUnit = 'week';
    public $page;


}