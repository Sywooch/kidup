<?php
namespace mail\widgets\column;

use notifications\widgets\BaseWidget;

/**
 * A Column widget which is used as follows:
 * {{ void(column.begin()) }}
 * My content
 * {{ void(column.end()) }}
 *
 * The options are the following:
 *      align  string     The inner alignment of the column (either left, right, center) (optional).
 *      width  int        The width of the column (optional).
 *
 * @package mail\widgets\column
 */
class Column extends BaseWidget
{

    public $width = '100%';
    public $align = 'left';

    public function init()
    {
        parent::init();
        ob_start();
    }

    public function run()
    {
        $content = ob_get_clean();
        return $this->renderTwig([
            'content' => $content,
            'width' => $this->width,
            'align' => $this->align
        ]);
    }

}