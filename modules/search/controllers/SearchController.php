<?php
namespace search\controllers;

use app\extended\web\Controller;
use \search\forms\Filter;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

/**
 * The item controller of the search module is used for handling actions related to searching items.
 */
class SearchController extends Controller
{

    /**
     * Define the behaviour.
     *
     * @return array
     */
    public function behaviors()
    {
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
    public function actionIndex($query = '')
    {
        // make sure that there is no footer and there is no container
        $this->noFooter = true;
        $this->noContainer = true;

        $model = new Filter();

        $model->load(\Yii::$app->request->get(), $model->formName());

        if(isset(\Yii::$app->request->get()[$model->formName()])){
            if(isset(\Yii::$app->request->get()[$model->formName()]['features']) || isset(\Yii::$app->request->get()[$model->formName()]['singleFeatures'])){
                $model->loadQueriedFeatures(\Yii::$app->request->get()[$model->formName()]);
            }
        }

        $model->query = $query;
        $model->setLocation();

        // create the data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getQuery(),
            'pagination' => [
                'pageSize' => 16,
            ],
        ]);

        if (Yii::$app->request->isPjax || Yii::$app->request->isPost) {
            return $this->renderAjax('index', [
                'model' => $model,
                'dataProvider' => $dataProvider
            ]);
        }

        // render the index
        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
}
