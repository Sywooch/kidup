<?php
namespace mail\widgets\space;

use mail\widgets\BaseWidget;

class Space extends BaseWidget
{
    public $height = 10;

    public function run()
    {
        return $this->renderTwig([
            'height' => $this->height
        ]);
    }

}