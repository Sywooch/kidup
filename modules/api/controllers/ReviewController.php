<?php
namespace api\controllers;

use api\models\Review;
use api\models\User;
use user\forms\Registration;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class ReviewController extends Controller
{
    public function init()
    {
        $this->modelClass = Review::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['index', 'view', 'create'],
            'user' => ['update']
        ];
    }


    public function actions()
    {
        $actions = parent::actions();

        return $actions;
    }
}