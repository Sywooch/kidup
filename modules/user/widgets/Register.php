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

use app\interfaces\RequestableWidgetInterface;
use app\modules\user\forms\Registration;
use app\modules\user\models\LoginForm;
use kartik\form\ActiveForm;
use yii\base\Model;
use yii\base\Widget;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
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
        $model = \Yii::createObject(Registration::className());

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
            return \Yii::$app->controller->redirect('@web/user/registration/post-registration');
        }

        return $this->render('register_modal', [
            'model'  => $model,
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