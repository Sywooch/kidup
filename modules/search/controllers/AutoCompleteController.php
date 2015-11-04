<?php
namespace search\controllers;

use app\extended\web\Controller;
use search\models\ItemSearch;
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
