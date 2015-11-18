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
 *
 * @package mail\widgets\paragraph
 */
class Paragraph extends BaseWidget
{

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
            'align' => $this->align,
            'content' => $content,
        ]);
    }

}