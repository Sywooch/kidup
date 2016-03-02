<?php

namespace home\widgets;

use Yii;

/**
 * Registers Meta tags that are used by fb for preview images
 */
class ReferralModal extends \yii\bootstrap\Widget
{
    public $autoOpen = false;

    public function run()
    {
        return false;
    }
}