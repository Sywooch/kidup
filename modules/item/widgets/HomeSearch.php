<?php


namespace app\modules\item\widgets;

use yii\bootstrap\Widget;

class HomeSearch extends Widget
{
    public $data;

    public function init(){

    }

    public function run()
    {

        return $this->render('home_search');
    }
}