<?php


namespace app\modules\item\widgets;

use app\interfaces\RequestableWidgetInterface;
use app\modules\item\models\Search;
use yii\bootstrap\Widget;

class MenuSearch extends Widget
{
    public $data;

    public function init(){

    }

    public function run()
    {
        \Yii::trace('menu_search_widget');

        $model = new Search([]);
        return $this->render('menu_search', [
            'model' => $model
        ]);
    }
}