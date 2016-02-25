<?php
namespace mail\widgets\line;

use notifications\widgets\BaseWidget;

/**
 * A Line widget which renders a line. Usage:
 * {{ line.widget({height:10, color:'#FF0000'}) | raw }}
 *
 * Options:
 *      height  int        The height (in pixels) of the line (optional).
 *      color   string     The color of the line (optional).
 *
 * @package mail\widgets\line
 */
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