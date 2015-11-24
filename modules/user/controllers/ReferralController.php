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

use app\extended\web\Controller;
use app\helpers\Event;
use app\helpers\SelectData;
use Carbon\Carbon;
use images\components\ImageManager;
use mail\models\Token;
use user\Finder;
use user\forms\LocationForm;
use user\forms\Settings;
use user\forms\Verification;
use user\models\Account;
use user\models\Profile;
use user\models\User;
use user\models\UserReferredUser;
use yii\authclient\ClientInterface;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

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
                        'roles' => ['@']
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
        $referralLink = Url::to('@web/?ref=' . \Yii::$app->user->identity->referral_code, true);

        $topList = (new UserReferredUser())->topList();

        return $this->render('index.twig', [
            'referralLink' => $referralLink,
            'count_total' => \Yii::$app->user->identity->getReferredUserCount(),
            'top_list' => $topList,
        ]);
    }
}
