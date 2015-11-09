<?php
namespace mail\widgets\line;

use mail\widgets\BaseWidget;

class Line extends BaseWidget
{
    public $color = '#F5F5F5';
    public $height = 1;

    public function run()
    {
        return $this->renderTwig([
            'color' => $this->color,
            'height' => $this->height,
        ]);
    }
}