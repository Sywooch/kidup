<?php

/*
 * This file is part of the app\modules project.
 *
 * (c) app\modules project <http://github.com/app\modules>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace app\modules\user\widgets;

use app\modules\user\forms\Registration;
use app\modules\user\models\User;
use kartik\form\ActiveForm;
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
        $model = new Registration();

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
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