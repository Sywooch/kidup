<?php
namespace mail\widgets\button;

use mail\widgets\BaseWidget;

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