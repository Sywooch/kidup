<?php
namespace app\extended\web;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This is a CUSTOMIZED view for yii, to wrap the jquery ready in a function to later be called in the app (usefull with pjax)
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class View extends \yii\web\View
{
    public $assetPackage = false;

    /*
     * Reigsters js variables into the scope
     * @param $array
     */
    public function registerJsVariables($array, $scope = 'window')
    {
        $js = "if(typeof($scope) === 'undefined'){ {$scope} = {}; };";

        foreach ($array as $varName => $value) {
            $varName = $scope . "." . $varName;
            if (is_object($value) || is_array($value)) {
                $value = Json::encode($value);
            } else {
                $value = "'" . $value . "'";
            }
            $js .= <<<JS
$varName = $value;
JS;
        }
        $this->registerJs($js, self::POS_BEGIN);
    }

    protected function compressJs($js)
    {
        if (YII_ENV !== 'dev') {
            $js = \JShrink\Minifier::minify($js);
        }
        return $js;
    }

    public function renderTwig($path)
    {
        $loader = new \Twig_Loader_Filesystem(\Yii::$aliases['@notification'] . '/widgets/button');
        $twig = new \Twig_Environment($loader, [
            'cache' => \Yii::$aliases['@runtime'] . '/twig'
        ]);
        return $twig->render($path . '.twig', ['x' => 9, 'this' => \Yii::$app->view]);
    }
}
