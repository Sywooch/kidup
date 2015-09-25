<?php
namespace app\components\extended;

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
        $jsFiles = [];
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

        if ($this->assetPackage !== false) {
            $this->webpackCssFiles = array_keys($cssFiles);
        } else {
            $lines[] = implode("\n", $this->webpackCssFiles);
        }

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

        if (YII_ENV == 'dev') {
            if ($this->assetPackage !== false) {
                $files = [
                    'js' => array_keys($this->webpackJsFiles),
                    'css' => ($this->webpackCssFiles),
                ];

                $commonAssets = Json::decode(file_get_contents(Yii::$aliases['@app'] . '/web/packages/common/common.json'));

                foreach ($files['js'] as $i => &$js) {
                    $js = str_replace('/vagrant/', './', $js);
                    if (strpos($js, 'http') === 0) {
                        unset($files['js'][$i]);
                        $lines[] = $this->webpackJsFiles[$js];
                    }
                    if (in_array($js, $commonAssets['js'])) {
                        unset($files['js'][$i]);
                    }
                }

                foreach ($files['css'] as $i => &$css) {
                    $css = str_replace('/vagrant/', './', $css);
                    if (strpos($css, 'http') === 0) {
                        unset($files['css'][$i]);
                        $lines[] = $this->webpackCssFiles[$css];
                    }
                    if (in_array($css, $commonAssets['css'])) {
                        unset($files['css'][$i]);
                    }
                }

                $files = [
                    'js' => array_values($files['js']),
                    'css' => array_values($files['css']),
                ];

                file_put_contents(Yii::$aliases['@app'] . '/web/packages/' . $this->assetPackage . '/' . $this->assetPackage . '.json',
                    Json::encode($files));
            } else {
                $lines[] = implode("\n", $this->jsFiles[self::POS_END]);
            }
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }
}
