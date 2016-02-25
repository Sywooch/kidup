<?php
namespace mail\widgets\greeter;

use notifications\widgets\BaseWidget;

/**
 * A Greeter widget:
 * {{ greeter.widget({name:'Alexander'}}
 *
 * Options:
 *      name  string     The name of the person to greet.
 *
 * @package mail\widgets\button
 */
class Greeter extends BaseWidget
{
    public $name = '';

    public function run()
    {
        return $this->renderTwig([
            'name' => $this->name
        ]);
    }

}