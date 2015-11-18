<?php
namespace mail\widgets\table;

use mail\widgets\BaseWidget;

/**
 * A Table widget which puts a table around text. It can be used in a Twig template as follows:
 * {{ void(table.begin({bgColor: '#FF0000'})) }}
 * My content
 * {{ void(table.end()) }}
 *
 * The options are the following:
 *      bgColor  string     The background color of the table (optional).
 *
 * @package mail\widgets\textBlock
 */
class Table extends BaseWidget
{

    public $bgColor = '#F5F5F5';
    public $outerTable = true;

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
            'outerTable' => $this->outerTable,
            'bgColor' => $this->bgColor,
        ]);
    }

}