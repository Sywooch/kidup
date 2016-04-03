<?php

namespace api;

class Module extends \yii\base\Module
{
    public $controllerNamespace = '\api\controllers';

    // basepath of all api versions
    public $basePath = 'api';

    public function registerUrls($rules, $version){
        $changeKeys = [];
        foreach ($rules as $key => &$rule) {
            if (is_array($rule)) {
                $rule['controller'] = $this->changeUrls($rule['controller'], $version);
            } else {
                $res = $this->changeUrls([$key => $rule], $version);
                $changeKeys[$key] = $this->basePath."/".$version."/" . $key;
                $rule = $res[$changeKeys[$key]];
            }
        }

        foreach ($changeKeys as $orig => $changeKey) {
            $rules[$changeKey] = $rules[$orig];
            unset($rules[$orig]);
        }
        \Yii::$app->urlManager->addRules($rules, false);
    }

    private function changeUrls($arr, $version)
    {
        $changeKeys = [];
        foreach ($arr as $k => &$r) {
            $changeKeys[$k] = $this->basePath."/".$version."/" . $k;
            $arr[$k] = "api/".$version."/" . $r;
        }

        foreach ($changeKeys as $orig => $changeKey) {
            $arr[$changeKey] = $arr[$orig];
            unset($arr[$orig]);
        }
        return $arr;
    }
}
