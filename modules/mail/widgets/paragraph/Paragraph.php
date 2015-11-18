<?php
namespace mail\widgets\paragraph;

use mail\widgets\BaseWidget;

/**
 * Wraps text in a paragraph HTML element. Usage:
 * {{ void(paragraph.begin({align: 'center'})) }}
 * My content
 * {{ void(paragraph.end()) }}
 *
 * Options:
 *      align  string  The alignment (left, right, center) of the text inside the paragraph (optional).
 *      size   int     The font size (in pixels) (optional).
 *
 * @package mail\widgets\paragraph
 */
class Paragraph extends BaseWidget
{

    public $align = 'left';
    public $size = 12;

    public function init()
    {
        parent::init();
        ob_start();
    }

    public function run()
    {
        $content = ob_get_clean();
        return $this->renderTwig([
            'align' => $this->align,
            'size' => $this->size,
            'content' => $content,
        ]);
    }

}