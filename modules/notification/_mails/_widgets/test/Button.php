<?php
namespace mail\widgets\button;

use notifications\widgets\BaseWidget;

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