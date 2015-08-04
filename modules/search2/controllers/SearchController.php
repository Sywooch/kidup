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

    /**
     * This is the default action.
     *
     * @return string
     */
    public function actionIndex($q = '') {
        // make sure that there is no footer and there is no container
        $this->noFooter = true;
        $this->noContainer = true;

        // load the item search model
        $model = new SearchModel();

        // load the parameters
        $model->loadParameters($q);

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
    public function actionResults($q) {
        // load the item search model
        $model = new SearchModel();

        // load the parameters
        $model->loadParameters($q);

        // load the search results
        $results = $model->findItems();

        // render the results
        return $this->renderPartial('results/index', [
            'results' => $results
        ]);
    }

    public function actionCategories(){
        return Json::encode(Category::find()->asArray()->all());
    }

}
