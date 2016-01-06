<?php
namespace search\controllers;

use app\extended\web\Controller;
use search\forms\Filter;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;

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
    public function actionIndex()
    {
        // make sure that there is no footer and there is no container
        $this->noFooter = true;
        $this->noContainer = true;

        $index = 'items';
        if(\Yii::$app->keyStore->fake_products){
            $index = 'items_fake';
        }

        Url::remember();
        // render the index
        return $this->render('index.twig', [
            'index' => $index
        ]);
    }

    public function actionTest(){
        $items = \item\models\Item::find()->where(['is_available' => 1])->all();
        (new \search\components\ItemSearchDb())->sync($items);
    }
}
