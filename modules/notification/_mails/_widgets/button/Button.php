<?php
namespace mail\widgets\button;

use notifications\widgets\BaseWidget;

/**
 * A Button widget which renders a button. Usage:
 * {{ button.widget({content:'My Button', url:'http://demo.com/', 'wide':true}) | raw }}
 *
 * Options:
 *      content  string     The title displayed on the button (required).
 *      url      string     The URL the button points to (required).
 *      wide     boolean    Whether or not the button has the full width (optional).
 *
 * @package mail\widgets\button
 */
class Button extends BaseWidget
{
    public $content = '';
    public $url = '';
    public $wide = true;

    public function run()
    {
        return $this->renderTwig([
            'content' => $this->content,
            'url' => $this->url,
            'wide' => $this->wide,
        ]);
    }

}