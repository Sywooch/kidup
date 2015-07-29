<?php

namespace app\modules\pages\controllers;

use app\components\WidgetRequest;
use app\controllers\Controller;
use yii\helpers\Url;
use app\components\Error;
use app\modules\item\models\Item;

class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'company' => [
                'class' => 'yii\web\ViewAction',
                'viewPrefix' => '@pages/views/company'
            ],
            'help' => [
                'class' => 'yii\web\ViewAction',
                'viewPrefix' => '@pages/views/help',
            ],
            'tutorial' => [
                'class' => 'yii\web\ViewAction',
                'viewPrefix' => '@pages/views/tutorial'
            ],
        ];
    }

    public function actionWordpress($page){
        $p = \Yii::$app->pages->get($page);
        if(isset($p['content'])){
            return $this->render('page_wrapper',[
                'content' => $p['content']
            ]);
        }
        return $this->goHome();
    }

}
