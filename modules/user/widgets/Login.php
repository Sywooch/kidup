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
use app\modules\user\models\LoginForm;
use app\modules\user\models\User;
use yii\base\Widget;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Login extends Widget
{
    public $data;
    public $validate = true;

    public function init($data = null)
    {

    }

    /** @inheritdoc */
    public function run()
    {
        $model = \Yii::createObject(\app\modules\user\forms\Login::className());

        if ($this->validate && $model->load(\Yii::$app->request->post()) && $model->login()) {
            return \Yii::$app->response->redirect(User::afterLoginUrl('login'));
        }

        return $this->render('login_modal', [
            'model' => $model,
            'action' => '@web/user/security/login'
        ]);
    }
}