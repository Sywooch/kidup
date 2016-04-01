<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com//>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace user\controllers;

use app\extended\web\Controller;
use item\models\item\Item;
use review\models\Review;
use user\models\profile\Profile;
use Yii;
use yii\filters\AccessControl;

/**
 * ProfileController shows users profiles.
 *
 * @property \user\Module $module
 */
class ProfileController extends Controller
{

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
        /**
         * @var $profile \user\models\profile\Profile
         */
        $profile = Profile::findOneOr404(['user_id' => $id]);

        $items = Item::find()
            ->where([
                'owner_id' => $profile->user_id,
                'is_available' => 1
            ])
            ->all()
        ;

        $reviewProvider = new \yii\data\ActiveDataProvider([
            'query' => Review::find()->where(['reviewed_id' => $profile->user_id, 'type' => Review::TYPE_USER_PUBLIC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $fbVerified = false;
        $twVerified = false;

        $accs = $profile->user->socialAccounts;
        foreach ($accs as $a) {
            if ($a->provider == 'facebook') {
                $fbVerified = true;
            }
            if ($a->provider == 'twitter') {
                $twVerified = true;
            }
        }

        return $this->render('show', [
            'fbVerified' => $fbVerified,
            'twVerified' => $twVerified,
            'profile' => $profile,
            'items' => $items,
            'reviewProvider' => $reviewProvider,
        ]);
    }
}
