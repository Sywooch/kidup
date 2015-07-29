<?php

namespace app\modules\item\widgets;

use app\interfaces\RequestableWidgetInterface;
use app\modules\item\models\Category;
use app\modules\item\models\Item;
use yii\bootstrap\Widget;

class HomeGrid extends Widget
{
    public $data;

    public function init($data = null)
    {

    }

    public function run()
    {
        $categories = Category::find()->all();
        $items = Item::find()->limit(3)->orderBy('RAND()')->where(['is_available' => 1])->all();

        return $this->render('home_grid', [
            'categories' => $categories,
            'items' => $items
        ]);
    }
}