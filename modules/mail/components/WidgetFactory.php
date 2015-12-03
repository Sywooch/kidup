<?php
namespace mail\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Widget factory which initializes all widgets.
 */
class WidgetFactory
{

    public static function camelize($word)
    {
        return lcfirst(implode('', array_map('ucfirst', array_map('strtolower', explode('_', $word)))));
    }

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
                $globals[$result] = "mail\\widgets\\" . strtolower($result) . "\\" . self::camelize($result);
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