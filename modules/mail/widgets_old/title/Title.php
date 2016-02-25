<?php
namespace mail\widgets\title;

use mail\widgets\BaseWidget;

/**
 * Display a title. Usage:
 * {{ title.widget({'content': 'My title'}) }}
 *
 * Options:
 *      title  string  The title to display.
 *
 * @package mail\widgets\title
 */
class Title extends BaseWidget
{

    public $content = '';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->renderTwig([
            'content' => $this->content,
        ]);
    }

}