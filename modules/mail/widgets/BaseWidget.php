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

//    public static function begin($config = [])
//    {
//        $begin = parent::begin($config);
//        return '{% block xxx %}{% endblock %}{% block xxx %}' ;
//
////        (new BaseWidget())->renderTwig([
////            'content' => '{% block xxx %}{% endblock %}{% block xxx %}'
////        ]);
//    }
//
//    public static function end()
//    {
//        $end =  parent::end();
//        return '{% endblock %}';
//    }
}