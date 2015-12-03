<?php
namespace mail\widgets\outerTable;

use mail\widgets\BaseWidget;

/**
 * A OuterTable widget which wraps content in an outer table. Usage:
 * {{ void(OuterTable.begin()) }}
 * My content
 * {{ void(OuterTable.end()) }}
 *
 * @package mail\widgets\textBlock
 */
class OuterTable extends BaseWidget
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