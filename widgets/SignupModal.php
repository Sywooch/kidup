<?php

namespace app\widgets;

use user\models\User;
use Yii;
use yii\helpers\Url;

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
        $referralUserImage = false;
        if (\Yii::$app->request->get("ref") !== null) {
            $this->autoOpen = true;
            $referralUser = User::find()->where(['referral_code' => \Yii::$app->request->get("ref")])->one();
            if ($referralUser !== null) {
                $referralUserImage = $referralUser->profile->getAttribute("img");
                $referralUser = $referralUser->profile->getName();
                $forceOpen = true;
                \Yii::$app->session->set('after_login_url', Url::to('@web/user/referral/index'));
            }
        }
        if ($this->autoOpen == false) {
            return $this->render('signup-modal', [
                'autoOpen' => $this->autoOpen,
                'referral_user' => $referralUser,
                'referral_user_image' => $referralUserImage
            ]);
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

        return $this->render('signup-modal', [
            'autoOpen' => $this->autoOpen,
            'referral_user' => $referralUser,
            'referral_user_image' => $referralUserImage
        ]);
    }
}