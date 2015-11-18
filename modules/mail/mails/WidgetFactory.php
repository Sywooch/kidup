<?php
namespace mail\mails;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Widget factory which initializes all widgets.
 */
class WidgetFactory
{
    /**
     * Load all widgets.
     */
    public static function registerWidgets()
    {
        $path = \Yii::$aliases['@mail'] . '/widgets/';
        $results = scandir($path);

        // loop through all widgets in the widget path
        $globals = [];
        $functions = [];
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') {
                continue;
            }

            if (is_dir($path . '/' . $result)) {
                $globals[$result] = "mail\\widgets\\" . $result . "\\" . ucfirst($result);
            }
        }

        // add the found functions to Twig
        $originalConfig = Yii::$app->getComponents()['view'];
        if (!isset($originalConfig['renderers']['twig']['functions'])) {
            $originalConfig['renderers']['twig']['functions'] = [];
        }
        if (!isset($originalConfig['renderers']['twig']['globals'])) {
            $originalConfig['renderers']['twig']['globals'] = [];
        }
        $originalConfig['renderers']['twig']['functions'] = ArrayHelper::merge($functions,
            $originalConfig['renderers']['twig']['functions']);
        $originalConfig['renderers']['twig']['globals'] = ArrayHelper::merge($globals,
            $originalConfig['renderers']['twig']['globals']);

        // finally, override the Twig configuration
        Yii::$app->setComponents([
            'view' => $originalConfig
        ]);
    }
}