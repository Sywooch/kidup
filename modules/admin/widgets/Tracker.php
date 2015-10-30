<?php
namespace admin\widgets;

use \images\components\ImageHelper;
use \user\models\Profile;
use kartik\widgets\Widget;

class Tracker extends Widget
{
    public function run()
    {
        return $this->render('tracker');
    }
}