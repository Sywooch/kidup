<?php
namespace mail\widgets;

use yii\base\Widget;

/**
 * The BasicWidget can be extended by other widgets. It renders the Twig file (in lowercase) which is
 * in the same folder as the widget file. For example, if a widget is named "Space" and located in
 * space/Space.php (relative to the current path for this widget), it will render space/space.twig on
 * rendering.
 *
 * Class BaseWidget
 * @package mail\widgets
 */
class BaseWidget extends Widget
{
    public function renderTwig($vars = [])
    {
        $path = strtolower(str_replace("views", "", $this->getViewPath()));
        $fileName = explode("/", $path);
        $fileName = $fileName[count($fileName) - 2];
        return $this->renderFile($path . $fileName . '.twig', $vars);
    }
}