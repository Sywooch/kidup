<?php
namespace mail\widgets;

use yii\base\Widget;

class BaseWidget extends Widget
{
    public function renderTwig($vars = [])
    {
        $path = strtolower(str_replace("views", "", $this->getViewPath()));
        $fileName = explode("/", $path);
        $fileName = $fileName[count($fileName) - 2];
        return $this->renderFile($path.$fileName.'.twig', $vars);
    }
}