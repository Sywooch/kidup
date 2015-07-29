<?php

namespace app\modules\item\controllers;

use app\controllers\Controller;
use app\modules\item\models\Search;
use Yii;
use app\modules\item\models\Category;
use yii\filters\AccessControl;

class SearchController extends Controller
{

    public $searchModel = null;
    public $dataProvider = null;
    public $filters = null;
    public $categories = null;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        // all users
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function initialize() {
        // the filters that should be loaded
        $this->filters = [
            [
                'view' => 'location',
                'label' => 'Location',
                'subfilter' => ['location', 'distance']
            ],
            [
                'view' => 'query',
                'label' => 'Search'
            ],
            [
                'view' => 'price',
                'label' => 'Price',
                'subfilter' => ['priceMin', 'priceMax']
            ],
            [
                'view' => 'age',
                'label' => 'Age'
            ],
            [
                'view' => 'categories',
                'label' => 'Categories'
            ],
        ];
        $this->noFooter = true;
        $this->noContainer = true;
        $this->searchModel = new Search(\Yii::$app->request->get());
        $this->dataProvider = $this->searchModel->search();
        $this->categories = Category::find()->all();
    }

    public function actionIndex()
    {
        if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX']==true) {
            return $this->actionResults();
        } else {
            $this->initialize();
            return $this->render('index', [
                'dataProvider' => $this->dataProvider,
                'searchModel' => $this->searchModel,
                'categories' => $this->categories,
                'filters' => $this->filters
            ]);
        }
    }

    public function actionResults()
    {
        $this->initialize();
        return $this->renderPartial('results', [
            'dataProvider' => $this->dataProvider,
            'searchModel' => $this->searchModel,
            'categories' => $this->categories,
            'filters' => $this->filters
        ]);
    }

}
