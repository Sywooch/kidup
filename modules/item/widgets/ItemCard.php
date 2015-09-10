<?php
namespace app\modules\item\widgets;

use yii\bootstrap\Widget;

class ItemCard extends Widget
{

    // the item model
    public $model;

    // whether or not to display a distance to the item (in km), if not, only
    // the city of the item will be displayed
    public $showDistance = false;

    public function init() {
        $this->model->name = $this->shortenize($this->model->name);
    }

    public function run()
    {
        return $this->render('item_card', [
            'model' => $this->model,
            'showDistance' => $this->showDistance
        ]);
    }

    private function shortenize($str) {
        $maxLength = 24;
        if (strlen($str) > $maxLength) {
            return substr($str, 0, $maxLength) . '...';
        }
        return $str;
    }

}