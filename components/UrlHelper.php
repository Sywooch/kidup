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

    /**
     * Returns whether the current site is on an danish domain
     * @return bool
     */
    public function isDanishDomain()
    {
        if(YII_ENV !== 'prod') return false;
        return strpos(\Yii::$app->request->getAbsoluteUrl(), "kidup.dk") > -1;
    }

    /**
     * Returns whether the current site is on an english domain
     * @return bool
     */
    public function isEnglishDomain()
    {
        if(YII_ENV !== 'prod') return false;
        return strpos(\Yii::$app->request->getAbsoluteUrl(), "kidup.co.uk") > -1;
    }
}