<?php
namespace mail\widgets\textBlock;

use mail\widgets\BaseWidget;

/**
 * A TextBlock widget which aims to organize texts in blocks. It can be used
 * in a Twig template as follows:
 * {{ void(textBlock.begin()) }}
 * My content
 * {{ void(textBlock.end()) }}
 *
 * Options:
 *      fontSize    int     The font-size in pixels (optional).
 *      row         boolean Whether or not to display a row (optional).
 *      align       string  The inner alignment of the column (either left, right, center) (optional).
 *      width       int     The width of the column (optional).
 *
 * @package mail\widgets\textBlock
 */
class TextBlock extends BaseWidget
{

    public $fontSize = 16;
    public $row = true;
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
            'fontSize' => $this->fontSize,
            'row' => $this->row,
            'width' => $this->width,
            'align' => $this->align
        ]);
    }

}