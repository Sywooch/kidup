<?php

namespace api\oauth2\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;


class DefaultController extends \yii\rest\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'refresh'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }
    
    public function actionIndex()
    {
        return [];
    }

    public function actionRefresh()
    {
        return [];
    }
}