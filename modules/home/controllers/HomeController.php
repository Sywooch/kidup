<?php

namespace app\modules\home\controllers;

use app\components\WidgetRequest;
use app\controllers\Controller;
use Yii;
use yii\web\Response;

class HomeController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    // allow all users
                    [
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],

        ];
    }

    public function actionIndex()
    {
        $this->transparentNav = true;
        $this->noContainer = true;

        $searchWidget = WidgetRequest::request(WidgetRequest::ITEM_HOME_SEARCH);
        $gridWidget = WidgetRequest::request(WidgetRequest::ITEM_HOME_GRID);

        $res = [
            'searchWidget' => $searchWidget,
            'gridWidget' => $gridWidget
        ];

        return $this->render('index', $res);
    }
}
