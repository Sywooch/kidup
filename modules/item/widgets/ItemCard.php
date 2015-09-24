<?php
namespace app\modules\item\widgets;

use yii\bootstrap\Widget;

class ItemCard extends Widget
{

    // the item model
    public $model;

    public $numberOfCards = 4;
    public $titleCutoff = 24;
    public $reviewCount = false;
    protected $rowClass = "item-card card-width col-xs-12 col-sm-6 col-md-4 col-lg-3";

    // whether or not to display a distance to the item (in km), if not, only
    // the city of the item will be displayed
    public $showDistance = false;

    public function init() {
        $this->model->name = $this->model->name;
        if($this->numberOfCards == 4){
            $this->rowClass = "item-card card-width col-xs-12 col-sm-6 col-md-3 col-lg-3";
        }
        if($this->numberOfCards == 3){
            $this->rowClass = "item-card card-width col-xs-12 col-sm-6 col-md-4 col-lg-4";
        }
        if($this->numberOfCards == 2){
            $this->rowClass = "item-card card-width col-xs-12 col-sm-6 col-md-6 col-lg-6";
        }
    }

    public function run()
    {
        return $this->render('item_card', [
            'model' => $this->model,
            'showDistance' => $this->showDistance,
            'rowClass' => $this->rowClass,
            'widget' => $this
        ]);
    }

}