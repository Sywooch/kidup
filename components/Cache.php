<?php

namespace app\components;

use Yii;
use yii\caching\ChainedDependency;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\web\View;

class Cache
{
    public $_name = '';
    private $_tags = false;
    private $_duration = 30;
    private $_variations = false;

    /**
     * @param string $name
     * @return Cache
     */
    public static function build($name)
    {
        $cache = new Cache();
        $cache->_name = $name;
        return $cache;
    }

    /**
     * @param int $time
     * @return $this
     */
    public function duration($time)
    {
        $this->_duration = $time;
        return $this;
    }

    /**
     * @param string|array $tags
     * @return $this
     */
    public function tags($tags)
    {
        $this->_tags = $tags;
        return $this;
    }

    /**
     * @param string|array $variations variations that might occur
     * @return $this
     */
    public function variations($variations)
    {
        if(is_array($variations)){
            foreach ($variations as $i => &$var) {
                if(!is_string($var)){
                    $var = json_encode($var);
                }
            }
        }else{
            $this->_variations = [$variations];
        }

        $this->_variations = $variations;
        return $this;
    }

    public function data($function)
    {
        if (!Yii::$app->keyStore->yii_cache) {
            return $function();
        }
        $this->_tags = ArrayHelper::merge([$this->_tags], ['data']);
        $data = Yii::$app->cache->get($this->constructName());
        if ($data != false) {
            return $data;
        } else {
            $data = $function();
            \Yii::$app->cache->set($this->constructName(), $data, $this->_duration, $this->buildDependenciesData());
            return $data;
        }
    }

    public function html($function, $doEcho = true)
    {
        if (!Yii::$app->keyStore->yii_cache) {
            return $function();
        }
        $this->_tags = ArrayHelper::merge([$this->_tags], ['html']);
        $view = new View();

        if ($view->beginCache($this->constructName(), ['dependency' => $this->buildDependenciesHtml(), 'duration' => $this->_duration])) {
            $res = $function();
            if ($doEcho) {
                echo $res;
                $view->endCache();
                return true;
            } else {
                $view->endCache();
                return $res;
            }
        }
        return false;
    }


    private function constructName()
    {
        if ($this->_variations !== false) {
            $this->_name .= '_' . implode('-', $this->_variations);
        }
        return $this->_name;
    }

    private function buildDependenciesData()
    {
        $deps = [];
        if ($this->_tags) {
            $deps[] = new TagDependency(['tags' => $this->_tags]);
        }
        $dependency = new ChainedDependency([
            'dependencies' => $deps
        ]);
        return $dependency;
    }

    private function buildDependenciesHtml()
    {
        $deps = [];
        if ($this->_tags) {
            $deps[] = new TagDependency(['tags' => $this->_tags]);
        }
        return [
            'class' => ChainedDependency::className(),
            'dependencies' => $deps,
        ];
    }

}