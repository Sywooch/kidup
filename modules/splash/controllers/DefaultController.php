<?php

namespace app\modules\splash\controllers;

use app\controllers\Controller;
use Yii;

class DefaultController extends Controller
{
    public function actionThanks()
    {
        $this->layout = 'splash';
        return $this->render('thanks');
    }

    public function actionIndex()
    {
        $this->layout = 'splash';

        $model = new \app\modules\splash\forms\SplashSignup();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            $this->redirect(['/thanks']);
        }
        return $this->render('splash', [
            'model' => $model
        ]);
    }
}
