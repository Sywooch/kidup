<?php

/*
 * This file is part of the app\modules project.
 *
 * (c) app\modules project <http://github.com/app\modules/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\user\controllers;

use app\modules\review\models\Review;
use app\modules\user\Finder;
use app\controllers\Controller;
use yii\base\Response;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\modules\user\models\Profile;
use app\modules\item\models\Item;
use Yii;
/**
 * ProfileController shows users profiles.
 *
 * @property \app\modules\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class ProfileController extends Controller
{
    /** @var Finder */
    protected $finder;

    /**
     * @param string $id
     * @param \yii\base\Module $module
     * @param Finder $finder
     * @param array $config
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
                    ['allow' => true, 'actions' => ['index'], 'roles' => ['@']],
                    ['allow' => true, 'actions' => ['show'], 'roles' => ['?', '@']],
                ]
            ],
        ];
    }

    /**
     * Redirects to current user's profile.
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        return $this->redirect(['show', 'id' => \Yii::$app->user->getId()]);
    }

    /**
     * Shows user's profile.
     * @param  integer $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionShow($id)
    {
        $profile = Profile::find()->where(['user_id' => $id])->one();
        if ($profile === null) {
            throw new NotFoundHttpException;
        }

        $itemProvider = new \yii\data\ActiveDataProvider([
            'query' => Item::find()->where(['owner_id' => $profile->user_id, 'is_available' => 1]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        $reviewProvider = new \yii\data\ActiveDataProvider([
            'query' => Review::find()->where(['reviewed_id' => $profile->user_id, 'type' => Review::TYPE_USER_PUBLIC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);



        return $this->render('show', [
            'profile' => $profile,
            'itemProvider' => $itemProvider,
            'reviewProvider' => $reviewProvider,
        ]);
    }
}
