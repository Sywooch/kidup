<?php
namespace notification\components;

class PushRenderer extends Renderer
{ 

    public $type = 'push';

    protected $templateFolder = '@notification-push';

    public function render($template = null)
    {
        if ($template === null) $template = $this->template;
        return $this->renderFromFile($template);
    }

}