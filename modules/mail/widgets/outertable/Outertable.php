<?php
namespace mail\widgets\outertable;

use mail\widgets\BaseWidget;

/**
 * A OuterTable widget which wraps content in an outer table. Usage:
 * {{ void(outertable.begin()) }}
 * My content
 * {{ void(outertable.end()) }}
 *
 * @package mail\widgets\textBlock
 */
class Outertable extends BaseWidget
{

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
        ]);
    }

}