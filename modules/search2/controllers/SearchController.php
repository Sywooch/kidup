<?php
namespace app\modules\search2\controllers;

use app\modules\item\models\Category;
use app\modules\search\models\ItemModel;
use app\modules\search2\models\SearchModel;
use Yii;
use app\controllers\Controller;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * The item controller of the search module is used for handling actions related to searching items.
 */
class SearchController extends Controller {

    /**
     * Define the behaviour.
     *
     * @return array
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'results'],
                'rules' => [
                    [
                        // all users
                        'allow' => true,
                        'actions' => ['index', 'results'],
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * This is the default action.
     *
     * @return string
     */
    public function actionIndex($q = '', $p = 0) {
        // make sure that there is no footer and there is no container
        $this->noFooter = true;
        $this->noContainer = true;

        // load the item search model
        $model = new SearchModel();

        // load the parameters
        $model->loadParameters($model->parseQueryString($q));

        $model->setPage($p);

        // load the search results
        $results = $model->findItems();

        // render the index
        return $this->render('index', [
            'model' => $model,
            'results' => $results
        ]);
    }

    /**
     * An action that only loads the results.
     *
     * @return string
     */
    public function actionResults($q, $p = 0) {
        // load the item search model
        $model = new SearchModel();

        // load the parameters
        $model->loadParameters($model->parseQueryString($q));

        $model->setPage($p);

        // load the search results
        $results = $model->findItems();

        // render the results
        return $this->renderPartial('results', [
            'results' => $results
        ]);
    }

    public function actionCategories(){
        return Json::encode(Category::find()->asArray()->all());
    }

}
