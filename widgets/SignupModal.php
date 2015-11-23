<?php

namespace app\widgets;

use app\components\Cache;
use images\components\ImageHelper;
use user\models\User;
use yii\helpers\Url;
use Yii;
use item\models\Item;

/**
 * Registers Meta tags that are used by fb for preview images
 */
class SignupModal extends \yii\bootstrap\Widget
{
    public $autoOpen = false;

    public function run()
    {
        $forceOpen = false;
        $referralUser = false;
        if (\Yii::$app->request->get("ref") !== null) {
            $this->autoOpen = true;
            $referralUser = User::find()->where(['referral_code' => \Yii::$app->request->get("ref")])->one();
            if ($referralUser !== null) {
                $referralUser = $referralUser->profile->getName();
                $forceOpen = true;
            }
        }
        if ($this->autoOpen == false) {
            return $this->render('signup-modal', ['autoOpen' => $this->autoOpen, 'referral_user' => $referralUser]);
        }

        if (!\Yii::$app->user->isGuest) {
            return '';
        }

        if (!$forceOpen) {
            if (\Yii::$app->session->has('signup-attempts')) {
                $attempts = Yii::$app->session->get('signup-attempts') + 1;
            } else {
                $attempts = 1;
            }
            Yii::$app->session->set('signup-attempts', $attempts);
            if ($attempts > 3 && $attempts !== 5 && $attempts !== 9) {
                Yii::$app->session->set('stop-attempting-signup', $attempts);
                return '';
            }
        }

        return $this->render('signup-modal', ['autoOpen' => $this->autoOpen, 'referral_user' => $referralUser]);
    }
}