<?php

namespace home\widgets;

use app\components\Cache;
use images\components\ImageHelper;
use user\models\User;
use yii\helpers\Url;
use Yii;
use item\models\Item;

/**
 * Registers Meta tags that are used by fb for preview images
 */
class ReferralModal extends \yii\bootstrap\Widget
{
    public $autoOpen = false;

    public function run()
    {
        return false;
        if (\Yii::$app->request->get("ref") == null && \Yii::$app->user->isGuest) {
            return $this->render('referral-modal.twig');
        }
        return '';
    }
}