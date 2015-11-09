<?php
namespace mail\widgets\textBlock;

use mail\widgets\BaseWidget;

class TextBlock extends BaseWidget
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