<?php
namespace api\controllers;

use api\models\Item;
use search\forms\Filter;
use yii\data\ActiveDataProvider;

class ItemController extends Controller
{
    public function init(){
        $this->modelClass = Item::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['index', 'view', 'search'],
            'user' => []
        ];
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['index']);
        unset($actions['update']);
        return $actions;
    }

    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => Item::find()->where(['is_available' => 1])
        ]);
    }

    /**
     * Search for items.
     *
     * @param page          page to start on (required, start with page=0)
     *
     * The following parameters are optional, but whenever a search feature is used, certain parameters
     * need to be set. Therefore, the documentation is grouped per feature to see what parameters
     * are related.
     *
     * Pricing: (priceUnit required, one of priceMin or priceMax required)
     * @param priceUnit     price_day, price_week, price_month
     * @param priceMin      minimum price
     * @param priceMax      maximum price
     *
     * Named location: (location required)
     * @param location      the name of the location (longitude and latitude will be automatically retrieved)
     *
     * Location: (longitude and latitude required)
     * @param longitude     the longitude of the location
     * @param latitude      the latitude of the location
     *
     * Categories: (categories required)
     * @param categories    an array of ids of categories
     *
     * @return array
     *              usedFilter      all filter that have been applied on the search
     *              numPages        the total number of pages
     *              numItems        the total number of items
     *              results         a JSON representation of all the retrieved items
     */
    public function actionSearch() {
        // fetch the parameters
        $params = \Yii::$app->request->get();

        // set some read-only parameters
        $pageSize = 12;

        // load the parameters
        $page = $params['page']; // page (starting from 0)

        // a list of all filters used (based on the parameters)
        $usedFilters = [];

        $model = new Filter();
        $model->page = $page;
        $model->pageSize = $pageSize;

        // load the pricing
        if (isset($params['priceUnit'])) {
            $usedFilters[] = 'priceUnit';
            $model->priceUnit = $params['priceUnit'];
            if (isset($params['priceMin'])) {
                $usedFilters[] = 'priceMin';
                $model->priceMin = $params['priceMin'];
            }
            if (isset($params['priceMax'])) {
                $usedFilters[] = 'priceMax';
                $model->priceMax = $params['priceMax'];
            }
        }

        // load location
        if (isset($params['location'])) {
            $usedFilters[] = 'namedLocation';
            $model->location = $params['location'];
        }

        // load location based on longitude and latitude
        if (isset($params['longitude']) && isset($params['latitude'])) {
            $usedFilters[] = 'location';
            $model->longitude = $params['longitude'];
            $model->latitude = $params['latitude'];
        }

        // load the categories
        if (isset($params['categories'])) {
            $model->categories = $params['categories'];
        }

        // now get the query
        $query = $model->getQuery(true);

        // and give back the results
        return [
            'usedFilters' => $usedFilters,
            'numPages' => ceil($model->estimatedResultCount / $pageSize),
            'numItems' => $model->estimatedResultCount,
            'results' => $query->all()
        ];
    }

}