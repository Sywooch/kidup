<?php
namespace app\components\extended;

use app\assets\Package;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\InvalidConfigException;
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

    public function init()
    {
        if ($this->assetPackage === false) {
            $this->assetPackage = Package::OTHER;
        }
        return parent::init();
    }

    public function endPage($ajaxMode = false)
    {
        $this->trigger(self::EVENT_END_PAGE);

        $content = ob_get_clean();

        echo strtr($content, [
            self::PH_HEAD => $this->renderHeadHtml(),
            self::PH_BODY_BEGIN => $this->renderBodyBeginHtml(),
            self::PH_BODY_END => $this->renderBodyEndHtml($ajaxMode),
        ]);

        $this->clear();
    }


    protected function renderHeadHtml()
    {
        $lines = [];
        $cssFiles = [];
        if (!empty($this->metaTags)) {
            $lines[] = implode("\n", $this->metaTags);
        }
        if (!empty($this->linkTags)) {
            $lines[] = implode("\n", $this->linkTags);
        }
        if (!empty($this->cssFiles)) {
            $cssFiles = $this->cssFiles;
        }
        if (!empty($this->css)) {
            $lines[] = implode("\n", $this->css);
        }
        if (!empty($this->jsFiles[self::POS_HEAD])) {
            $jsFiles = $this->jsFiles[self::POS_HEAD];
        }
        if (!empty($this->js[self::POS_HEAD])) {
            $lines[] = Html::script(implode("\n", $this->js[self::POS_HEAD]), ['type' => 'text/javascript']);
        }

        $this->webpackCssFiles = array_keys($cssFiles);
        $lines = ArrayHelper::merge($this->processPackageFiles('css'), $lines);

        return empty($lines) ? '' : implode("\n", $lines);
    }

    /**
     * Renders the content to be inserted at the beginning of the body section.
     * The content is rendered using the registered JS code blocks and files.
     * @return string the rendered content
     */
    protected function renderBodyBeginHtml()
    {
        $lines = [];
        if (!empty($this->jsFiles[self::POS_BEGIN])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_BEGIN]);
        }
        if (!empty($this->js[self::POS_BEGIN])) {
            $lines[] = Html::script(implode("\n", $this->js[self::POS_BEGIN]), ['type' => 'text/javascript']);
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

        if (!empty($this->jsFiles[self::POS_END])) {
            $this->webpackJsFiles = ArrayHelper::merge($this->webpackJsFiles, $this->jsFiles[self::POS_END]);
        }

        if ($ajaxMode) {
            $scripts = [];
            if (!empty($this->js[self::POS_END])) {
                $scripts[] = implode("\n", $this->js[self::POS_END]);
            }
            if (!empty($this->js[self::POS_READY])) {
                $scripts[] = implode("\n", $this->js[self::POS_READY]);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $scripts[] = implode("\n", $this->js[self::POS_LOAD]);
            }
            if (!empty($scripts)) {
                $lines[] = Html::script(implode("\n", $scripts), ['type' => 'text/javascript']);
            }
        } else {
            if (!empty($this->js[self::POS_END])) {
                $lines[] = Html::script(implode("\n", $this->js[self::POS_END]), ['type' => 'text/javascript']);
            }
            if (!empty($this->js[self::POS_READY])) {
                // customization
                $js = "var yiiOnReadyFunction = function(){\n" . implode("\n", $this->js[self::POS_READY]) . "\n};";
                $js .= "jQuery(document).ready(yiiOnReadyFunction);";

                // customization out
                $lines[] = Html::script($js, ['type' => 'text/javascript']);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $js = "jQuery(window).load(function () {\n" . implode("\n", $this->js[self::POS_LOAD]) . "\n});";
                $lines[] = Html::script($js, ['type' => 'text/javascript']);
            }
        }

        $this->webpackJsFiles = array_keys($this->webpackJsFiles);

        $lines = ArrayHelper::merge($this->processPackageFiles('js'), $lines);

        return empty($lines) ? '' : implode("\n", $lines);
    }

    protected function processPackageFiles($type)
    {
        $lines = [];
        $commonPath = 'common/common.json';
        $packagePath = $this->assetPackage . '/' . $this->assetPackage . '.json';

        $adapter = new \League\Flysystem\Adapter\Local(Yii::$aliases['@app'] . '/web/packages/');
        $filesystem = new Filesystem($adapter);
        if (!$filesystem->has($packagePath)) {
            $filesystem->write($packagePath, Json::encode(['css' => [], 'js' => []]));
        }
        if (!$filesystem->has($commonPath)) {
            $filesystem->write($commonPath, Json::encode(['css' => [], 'js' => []]));
        }
        $commonAssets = Json::decode($filesystem->read($commonPath));
        $packageAssets = Json::decode($filesystem->read($packagePath));
        $packageChange = false;

        if($type == 'js'){
            foreach ($this->webpackJsFiles as $i => &$js) {
                $js = str_replace('/vagrant/', './', $js);
                if (strpos($js, 'http') === 0) {
                    unset($this->webpackJsFiles[$i]);
                    $lines[] = Html::jsFile($js);
                } elseif (in_array($js, $commonAssets['js'])) {
                    unset($this->webpackJsFiles[$i]);
                } else {
                    $packageChange = true;
                    if(!in_array($js, $packageAssets['js'])){
                        $packageAssets['js'][] = $js;
                    }
                }
            }
        }

        if($type == 'css'){
            foreach ($this->webpackCssFiles as $i => &$css) {
                $css = str_replace('/vagrant/', './', $css);
                if (strpos($css, 'http') === 0) {
                    unset($this->webpackCssFiles[$i]);
                    $lines[] = Html::cssFile($css);
                } elseif (in_array($css, $commonAssets['css'])) {
                    unset($this->webpackCssFiles[$i]);
                } else {
                    $packageChange = true;
                    if(!in_array($css,$packageAssets['css'])){
                        $packageAssets['css'][] = $css;
                    }
                }
            }
        }

        if ($packageChange) {
            $filesystem->update($packagePath, Json::encode($packageAssets));
        }

        if ($type == 'js') {
            return ArrayHelper::merge($lines,[
                Html::jsFile(Yii::$aliases['@web'] . "/packages/common/common.js"),
                Html::jsFile(Yii::$aliases['@web'] . "/packages/{$this->assetPackage}/{$this->assetPackage}.js"),
            ]);
        } else {
            return ArrayHelper::merge($lines,[
                Html::cssFile(Yii::$aliases['@web'] . "/packages/common/common.css"),
                Html::cssFile(Yii::$aliases['@web'] . "/packages/{$this->assetPackage}/{$this->assetPackage}.css"),
            ]);
        }
    }
}
