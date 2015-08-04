<?php

namespace app\modules\home\controllers;

use app\components\WidgetRequest;
use app\controllers\Controller;
use Yii;

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

        $searchWidget = $this->cache('xxx', function () {
            return WidgetRequest::request(WidgetRequest::ITEM_HOME_SEARCH);
        });

        $gridWidget = $this->cache('xxx2', function () {
            return WidgetRequest::request(WidgetRequest::ITEM_HOME_GRID);
        });

        $res = [
            'searchWidget' => $searchWidget,
            'gridWidget' => $gridWidget
        ];

        return $this->render('index', $res);
    }

    public function cache($id, $function)
    {
        $data = Yii::$app->cache->get($id);
        if ($data != false) {
            return $data;
        }
        $data = $function();
        Yii::$app->cache->set($id, $data);
        return $function();
    }
}
