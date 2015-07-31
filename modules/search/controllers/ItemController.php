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
     * Actions before loading the requested action.
     *
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action){
        // force assets to republish when in debug mode
        if (defined('YII_DEBUG') && YII_DEBUG) {
            Yii::$app->assetManager->forceCopy = true;
        }
        return parent::beforeAction($action);
    }

    /**
     * This is the default action.
     *
     * @return string
     */
    public function actionIndex() {
        // make sure that there is no footer and there is no container
        $this->noFooter = true;
        $this->noContainer = true;

        // render the index
        return $this->render('index', []);
    }

}
