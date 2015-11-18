<?php
namespace mail\widgets\textBlock;

use mail\widgets\BaseWidget;

/**
 * A TextBlock widget which aims to organize texts in blocks. It can be used
 * in a Twig template as follows:
 * {{ void(textBlock.begin()) }}
 * My content
 * {{ void(textBlock.end()) }}
 *
 * @package mail\widgets\textBlock
 */
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