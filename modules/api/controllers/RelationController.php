<?php
namespace api\controllers;

use api\models\Review;
use user\models\user\User;

class RelationController extends \yii\base\Controller
{
    private $classMap;
    private $controllerMap;

    public function init(){
        parent::init();
        $this->classMap = [
            'users' => User::className(),
            'reviews' => Review::className()
        ];
        $this->controllerMap = [
            'users' => UserController::className(),
            'reviews' => ReviewController::className(),
        ];
    }


    public function actionIndex()
    {
        $params = \Yii::$app->request->get();

        $class = \Yii::createObject($this->classMap[$params['relation1']]);
        $controllerName = $this->controllerMap[$params['relation1']];
        $controller = new $controllerName(
            null,
            \Yii::$app->getModule('api')
        );

        if(\Yii::$app->request->isGet){
            $controller->relationalWhere = [];
            if(isset($params['relation1Id'])){
                return $controller->runAction('view', ['id' => $params['id']]);
            }else{
                return $controller->runAction('index', []);
            }
        }

        return false;
    }

}