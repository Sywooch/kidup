<?php

namespace app\components;

use yii\base\Component;

class UrlHelper extends Component
{
    public function isHome()
    {
        $url = @explode("?", @\Yii::$app->request->getUrl())[0];
        return ($url == '/' || $url == '/home');
    }

    public function isAPI()
    {
        return  strpos(@\Yii::$app->request->getUrl(), '/api/v') !== false;
    }

    public function isSearch()
    {
        $url = @explode("?", @\Yii::$app->request->getUrl())[0];
        return ($url == '/search');
    }
}