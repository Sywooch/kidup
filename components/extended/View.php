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

    public $assetPackage;

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

        $this->webpackCssFiles = array_keys($cssFiles);

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
            $this->webpackJsFiles = ArrayHelper::merge($this->webpackJsFiles, $this->js[self::POS_END]);
            $this->webpackJsFiles = ArrayHelper::merge($this->webpackJsFiles, $this->js[self::POS_READY]);
            $this->webpackJsFiles = ArrayHelper::merge($this->webpackJsFiles, $this->js[self::POS_LOAD]);

            if (!empty($scripts)) {
                $lines[] = Html::script(implode("\n", $scripts), ['type' => 'text/javascript']);
            }
        } else {
            if (!empty($this->js[self::POS_END])) {
                $this->webpackJsFiles = ArrayHelper::merge($this->webpackJsFiles, $this->js[self::POS_END]);
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
        $files = [
            'js' => array_keys($this->webpackJsFiles),
            'css' => ($this->webpackCssFiles),
        ];

        file_put_contents(Yii::$aliases['@app'] . '/web/packages/home/home.package.json',
            Json::encode($files));

        return empty($lines) ? '' : implode("\n", $lines);
    }
}
