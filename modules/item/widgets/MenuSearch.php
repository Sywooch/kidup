<?php


namespace app\modules\item\widgets;

use app\interfaces\RequestableWidgetInterface;
use app\modules\item\models\Search;
use yii\bootstrap\Widget;
use yii\helpers\Url;

class MenuSearch extends Widget
{
    public $data;

    public function init(){

    }

    public function run()
    {
        \Yii::trace('menu_search_widget');
        if(strpos(Url::current(), '/web/search') == 0){
            return '';
        }
        $model = new Search([]);
        return $this->render('menu_search', [
            'model' => $model
        ]);
    }
}