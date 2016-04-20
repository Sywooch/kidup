<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com/app\models/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace user\controllers;

use app\components\web\Controller;
use user\models\userReferredUser\UserReferredUser;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * SettingsController manages updating user settings (e.g. profile, email and password).
 *
 * @property \user\Module $module
 */
class ReferralController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                        ],
                        'roles' => ['@', '?']
                    ],
                ]
            ],
        ];
    }

    /**
     * Shows referral link and statistics
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $referralLink = !\Yii::$app->user->isGuest ? Url::to('@web/?ref=' . \Yii::$app->user->identity->referral_code, true) : '';

        $topList = (new UserReferredUser())->topList();

        $referralCount = !\Yii::$app->user->isGuest ? \Yii::$app->user->identity->getReferredUserCount() : 0;

        \Yii::$app->session->set('after_login_url', Url::to('@web/user/referral/index', true));

        return $this->render('index.twig', [
            'referralLink' => $referralLink,
            'count_total' => $referralCount,
            'top_list' => $topList,
        ]);
    }
}
