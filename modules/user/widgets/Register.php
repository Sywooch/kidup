<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace user\widgets;

use kartik\form\ActiveForm;
use user\forms\Registration;
use user\models\User;
use yii\base\Model;
use yii\base\Widget;
use yii\web\Response;

/**
 */
class Register extends Widget
{
    public $data;

    public function init($data = null)
    {

    }

    /** @inheritdoc */
    public function run()
    {
        if(!\Yii::$app->user->isGuest){
            return false;
        }
        $model = new Registration();

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {

            if(YII_ENV == 'test'){
                return false;
            }
            return \Yii::$app->controller->redirect(User::afterLoginUrl('registration'));
        }

        return $this->render('register_modal', [
            'model' => $model,
        ]);
    }

    protected function performAjaxValidation(Model $model)
    {
        if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            echo json_encode(ActiveForm::validate($model));
            \Yii::$app->end();
        }
    }
}