<?php
namespace app\modules\search\controllers;

use app\controllers\Controller;
use Yii;
use yii\filters\AccessControl;

class ItemController extends Controller {

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

    public function actionIndex() {
        $this->noFooter = true;
        $this->noContainer = true;
        return $this->render('index', []);
    }

}
