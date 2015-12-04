<?php
namespace app\extended\web;

use app\assets\Package;
use app\components\Cache;
use League\Flysystem\Filesystem;
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

    private $webpackCssFiles = [];
    private $webpackJsFiles = [];

    protected function renderHeadHtml()
    {
        $lines = [];
        if (!empty($this->metaTags)) {
            $lines[] = implode("\n", $this->metaTags);
        }

        if (!empty($this->linkTags)) {
            $lines[] = implode("\n", $this->linkTags);
        }
        if (!empty($this->cssFiles)) {
            $lines[] = implode("\n", $this->cssFiles);
            $this->webpackCssFiles = ArrayHelper::merge($this->webpackCssFiles, $this->cssFiles);
        }
        if (!empty($this->css)) {
            $lines[] = implode("\n", $this->css);
        }
        if (!empty($this->jsFiles[self::POS_HEAD])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_HEAD]);
        }
        if (!empty($this->js[self::POS_HEAD])) {
            $lines[] = Html::script($this->compressJs(implode("\n", $this->js[self::POS_HEAD])),
                ['type' => 'text/javascript']);
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }

    /**
     * Renders the content to be inserted at the end of the body section.
     * The content is rendered using the registered JS code blocks and files.
     * @param boolean $ajaxMode whether the view is rendering in AJAX mode.
     * If true, the JS scripts registered at [[POS_READY]] and [[POS_LOAD]] positions
     * will be rendered at the end of the view like normal scripts.
     * @return string the rendered content
     */
    protected function renderBodyEndHtml($ajaxMode)
    {
        $lines = [];

        // todo ugly fix to prevent the all package from being loaded after ready
        if (isset($this->jsFiles[self::POS_READY])) {
            foreach ($this->jsFiles[self::POS_READY] as $file => $str) {
                if (strpos($file, "release-assets/js/common") === 1) {
                    $this->jsFiles[self::POS_END] = ArrayHelper::merge([$file => $str], $this->jsFiles[self::POS_END]);
                    unset($this->jsFiles[self::POS_READY][$file]);
                }
            }
        }

        if (!empty($this->jsFiles[self::POS_END])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_END]);
        }
        $this->webpackJsFiles = ArrayHelper::merge($this->webpackJsFiles, $this->jsFiles[\yii\web\View::POS_END]);

        if ($ajaxMode) {
            $scripts = [];
            if (!empty($this->js[\yii\web\View::POS_END])) {
                $scripts[] = implode("\n", $this->js[\yii\web\View::POS_END]);
            }
            if (!empty($this->js[\yii\web\View::POS_READY])) {
                $scripts[] = implode("\n", $this->js[\yii\web\View::POS_READY]);
            }
            if (!empty($this->js[\yii\web\View::POS_LOAD])) {
                $scripts[] = implode("\n", $this->js[\yii\web\View::POS_LOAD]);
            }
            if (!empty($scripts)) {
                $lines[] = Html::script($this->compressJs(implode("\n", $scripts)), ['type' => 'text/javascript']);
            }
        } else {
            if (!empty($this->js[\yii\web\View::POS_END])) {
                $lines[] = Html::script($this->compressJs(implode("\n", $this->js[\yii\web\View::POS_END])),
                    ['type' => 'text/javascript']);
            }
            if (!empty($this->js[\yii\web\View::POS_READY])) {
                // customization
                $js = "var yiiOnReadyFunction = function(){\n" . implode("\n",
                        $this->js[\yii\web\View::POS_READY]) . "\n};";
                $js .= "jQuery(document).ready(yiiOnReadyFunction);";

                // customization out
                $lines[] = Html::script($this->compressJs($js), ['type' => 'text/javascript']);
            }
            if (!empty($this->js[\yii\web\View::POS_LOAD])) {
                $js = "jQuery(window).load(function () {\n" . implode("\n",
                        $this->js[\yii\web\View::POS_LOAD]) . "\n});";
                $lines[] = Html::script($this->compressJs($js), ['type' => 'text/javascript']);
            }
        }

        $this->processPackageFiles();

        return empty($lines) ? '' : implode("\n", $lines);
    }

    protected function processPackageFiles()
    {
        $commonPath = 'watch.json';

        $adapter = new \League\Flysystem\Adapter\Local(Yii::$aliases['@app'] . '/web/packages/');
        $filesystem = new Filesystem($adapter);

        if (!$filesystem->has($commonPath)) {
            $filesystem->write($commonPath, Json::encode(['css' => [], 'js' => []]));
        }
        $origFile = $filesystem->read($commonPath);
        $commonAssets = Json::decode($origFile);
        foreach ($this->webpackCssFiles as $file => $html) {
            if (strpos($file, 'http') === 0) {
                continue;
            }
            $commonAssets['css'][] = str_replace(".css", ".less", 'web' . $file);
        }
        foreach ($this->webpackJsFiles as $file => $html) {
            $commonAssets['js'][] = 'web' . $file;
        }

        $commonAssets['js'] = array_values(array_unique($commonAssets['js']));
        $commonAssets['css'] = array_values(array_unique($commonAssets['css']));

        if ($origFile !== Json::encode($origFile)) {
            $filesystem->update($commonPath, Json::encode($commonAssets));
        }
    }

    /**
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
        $loader = new \Twig_Loader_Filesystem(\Yii::$aliases['@mail'] . '/widgets/button');
        $twig = new \Twig_Environment($loader, [
            'cache' => \Yii::$aliases['@runtime'] . '/twig'
        ]);
        return $twig->render($path . '.twig', ['x' => 9, 'this' => \Yii::$app->view]);
    }
}
