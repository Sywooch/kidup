<?php
namespace mail\widgets\space;

use mail\widgets\BaseWidget;

/**
 * A space widget which renders vertical space. Usage:
 * {{ space.widget({height:10}) | raw }}
 *
 * Options:
 *      height  int     The height (in pixels) of the vertical spacing (optional).
 *
 * @package mail\widgets\space
 */
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