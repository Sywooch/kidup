<?php
namespace admin\widgets;

use kartik\widgets\Widget;

class Tracker extends Widget
{
    public function run()
    {
        return $this->render('tracker');
    }
}