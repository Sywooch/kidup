<?php


namespace app\modules\item\widgets;

use app\interfaces\RequestableWidgetInterface;
use app\modules\item\models\Search;
use yii\base\Model;
use yii\bootstrap\Widget;

class HomeSearch extends Widget
{
    public $data;

    public function init(){

    }

    public function run()
    {
        $model = new Search([]);

        return $this->render('home_search', [
            'model' => $model
        ]);
    }
}