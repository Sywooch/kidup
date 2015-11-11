<?php
namespace mail\mails;

use mail\components\MailUrl;
use user\models\Profile;
use user\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Login form
 */
class WidgetFactory
{
    /**
     * Register all email widgets to the global twig scope
     */
    public static function registerWidgets(){
        $path = \Yii::$aliases['@mail'].'/widgets/';
        $results = scandir($path);

        $globals = [];
        $functions = [];
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;

            if (is_dir($path . '/' . $result)) {
                $globals[$result] = "mail\\widgets\\".$result."\\".ucfirst($result);
            }
        }

        $originalConfig = Yii::$app->getComponents()['view'];
        if(!isset($originalConfig['renderers']['twig']['functions'])){
            $originalConfig['renderers']['twig']['functions'] = [];
        }
        if(!isset($originalConfig['renderers']['twig']['globals'])){
            $originalConfig['renderers']['twig']['globals'] = [];
        }
        $originalConfig['renderers']['twig']['functions'] = ArrayHelper::merge($functions,
            $originalConfig['renderers']['twig']['functions']);
        $originalConfig['renderers']['twig']['globals'] = ArrayHelper::merge($globals,
            $originalConfig['renderers']['twig']['globals']);

        Yii::$app->setComponents([
            'view' => $originalConfig
        ]);
    }
}