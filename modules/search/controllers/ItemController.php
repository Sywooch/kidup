<?php
namespace app\modules\search\controllers;

use Yii;
use app\controllers\Controller;
use yii\filters\AccessControl;

/**
 * The item controller of the search module is used for handling actions related to searching items.
 *
 * Class ItemController
 * @package app\modules\search\controllers
 * @author kevin91nl
 */
class ItemController extends Controller {

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
     * This action is called on default.
     *
     * @return string
     */
    public function actionIndex() {
        $this->noFooter = true;
        $this->noContainer = true;
        return $this->render('index', []);
    }

}
