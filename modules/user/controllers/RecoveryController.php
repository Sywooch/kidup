<?php

/*
 * This file is part of the app\modules project.
 *
 * (c) app\modules project <http://github.com/app\models/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\user\controllers;

use app\modules\item\models\Location;
use app\modules\mail\models\Token;
use app\modules\user\Finder;
use app\modules\user\forms\Recovery;
use yii\base\Model;
use app\controllers\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * RecoveryController manages password recovery process.
 *
 * @property \app\modules\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RecoveryController extends Controller
{
    /** @var Finder */
    protected $finder;

    /**
     * @param string           $id
     * @param \yii\base\Module $module
     * @param Finder           $finder
     * @param array            $config
     */
    public function __construct($id, $module, Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($id, $module, $config);
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['request', 'reset'], 'roles' => ['?']],
                ]
            ],
        ];
    }

    /**
     * Shows page where user can request password recovery.
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRequest()
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException;
        }

        $model = \Yii::createObject([
            'class'    => Recovery::className(),
            'scenario' => 'request',
        ]);

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->sendRecoveryMessage()) {
            return $this->redirect('@web/home');
        }

        return $this->render('request', [
            'model' => $model,
        ]);
    }

    /**
     * Displays page where user can reset password.
     * @param  integer $id
     * @param  string  $code
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionReset($id, $code)
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException;
        }

        /** @var Token $token */
        $token = Token::find()->where(['user_id' => $id, 'code' => $code, 'type' => Token::TYPE_RECOVERY])->one();

        if ($token === null || $token->isExpired || $token->user === null) {
            \Yii::$app->session->setFlash('danger', \Yii::t('user', 'Recovery link is invalid or expired. Please try requesting a new one.'));
            return $this->render('/message', [
                'title'  => \Yii::t('user', 'Invalid or expired link'),
                'module' => $this->module,
            ]);
        }

        $model = \Yii::createObject([
            'class'    => Recovery::className(),
            'scenario' => 'reset',
        ]);

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->resetPassword($token)) {
            return $this->redirect('@web/user/settings/profile');
        }

        return $this->render('reset', [
            'model' => $model,
        ]);
    }

    /**
     * Performs ajax validation.
     * @param Model $model
     * @throws \yii\base\ExitException
     */
    protected function performAjaxValidation(Model $model)
    {
        if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            echo json_encode(ActiveForm::validate($model));
            \Yii::$app->end();
        }
    }
}
