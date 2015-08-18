<?php


namespace app\modules\item\widgets;

use app\modules\search\models\SearchModel;
use yii\bootstrap\Widget;

class MenuSearch extends Widget
{
    public $data;

    public function init()
    {

    }

    public function run()
    {
        if (
            \Yii::$app->controller->className() == 'app\\modules\\search\\controllers\\SearchController' ||
            \Yii::$app->controller->className() == 'app\\modules\\home\\controllers\\HomeController'
        ) {
            return '';
        }
        $model = new SearchModel([]);
        return $this->render('menu_search', [
            'model' => $model
        ]);
    }
}