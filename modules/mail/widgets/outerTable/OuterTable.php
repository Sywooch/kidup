<?php
namespace mail\widgets\outerTable;

use mail\widgets\BaseWidget;

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