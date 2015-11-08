<?php
namespace mail\widgets\table;

use mail\widgets\BaseWidget;

class Table extends BaseWidget
{

    public $bgColor = '#EEEEEE';

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
            'bgColor' => $this->bgColor,
        ]);
    }

}