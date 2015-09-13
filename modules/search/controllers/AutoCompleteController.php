<?php
namespace app\modules\search\controllers;

use app\controllers\Controller;
use app\modules\item\models\Category;
use app\modules\search\models\ItemModel;
use app\modules\search\models\SearchModel;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * The item controller of the search module is used for handling actions related to searching items.
 */
class AutoCompleteController extends Controller
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
    public function actionIndex($q='')
    {
        $categories = Category::find()->select('name as value, id as category_id')->where('name like :q')->params([
            ':q' => $q."%"
        ])->asArray()->limit(5)->all();
        return Json::encode(
            $categories
        );
    }


}
