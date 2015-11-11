<?php
namespace mail\widgets\paragraph;

use mail\widgets\BaseWidget;

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