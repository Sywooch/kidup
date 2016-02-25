<?php
namespace mail\widgets\table;

use notifications\widgets\BaseWidget;

/**
 * A Table widget which puts a table around text. It can be used in a Twig template as follows:
 * {{ void(table.begin({bgColor: '#FF0000'})) }}
 * My content
 * {{ void(table.end()) }}
 *
 * The options are the following:
 *      bgColor         string     The background color of the table (optional).
 *      borderColor     string     The color of the border.
 *      borderSize      int        The size (in pixels) of the border.
 *      borderBottom    boolean    Whether or not the bottom border should be shown (available when borderSize > 0).
 *      borderTop       boolean    Whether or not the top border should be shown (available when borderSize > 0).
 *      borderLeft      boolean    Whether or not the left border should be shown (available when borderSize > 0).
 *      borderRight     boolean    Whether or not the right border should be shown (available when borderSize > 0).
 *
 * @package mail\widgets\textBlock
 */
class Table extends BaseWidget
{

    public $bgColor = '#F5F5F5';
    public $outerTable = true;
    public $borderColor = '#999999';
    public $borderSize = 0;
    public $borderBottom = true;
    public $borderTop = true;
    public $borderLeft = true;
    public $borderRight = true;

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
            'borderColor' => $this->borderColor,
            'borderSize' => $this->borderSize,
            'borderBottom' => $this->borderBottom,
            'borderTop' => $this->borderTop,
            'borderLeft' => $this->borderLeft,
            'borderRight' => $this->borderRight,
        ]);
    }

}