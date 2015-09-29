<?php
namespace app\modules\search\controllers;

use app\components\Cache;
use app\extended\web\Controller;
use app\modules\item\models\Category;
use app\modules\search\models\ItemModel;
use app\modules\search\models\ItemSearch;
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
    public function actionIndex($q = '')
    {
        return Json::encode(
            (new ItemSearch())->getSuggestions($q)
        );
    }
}
