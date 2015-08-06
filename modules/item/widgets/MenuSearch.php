<?php


namespace app\modules\item\widgets;

use app\modules\search\models\SearchModel;
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
        if(strpos(Url::current(), 'web/search') == 1){
            return '';
        }
        $model = new SearchModel([]);
        return $this->render('menu_search', [
            'model' => $model
        ]);
    }
}