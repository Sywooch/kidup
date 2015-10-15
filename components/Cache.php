<?php

namespace app\components;

use Yii;
use yii\caching\ChainedDependency;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\web\Response;
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
        @$d = debug_backtrace()[0]['file'];
        if($d){
            $name = $d.$name;
        }
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

    /**
     * @param callable $function
     * @return mixed result
     */
    public function data($function)
    {
        if (!Yii::$app->keyStore->yii_cache) {
            return $function();
        }
        if(is_array($this->_tags)){
            $this->_tags = ArrayHelper::merge($this->_tags, ['data']);
        }else{
            $this->_tags = ['data'];
        }
        $data = Yii::$app->cache->get($this->constructName());
        if ($data != false) {
            return $data;
        } else {
            $data = $function();
            \Yii::$app->cache->set($this->constructName(), $data, $this->_duration, $this->buildDependenciesData());
            return $data;
        }
    }

    /**
     * @param callable $function
     * @return mixed result
     */
    public function html($function, $doEcho = true)
    {
        if (!Yii::$app->keyStore->yii_cache) {
            return $function();
        }
        if(is_array($this->_tags)){
            $this->_tags = ArrayHelper::merge($this->_tags, ['html']);
        }else{
            $this->_tags = ['html'];
        }
        $view = new View();

        if ($view->beginCache($this->constructName(), ['dependency' => $this->buildDependenciesHtml(), 'duration' => $this->_duration])) {
            if($function instanceof Response){
                return $function;
            }
            $res = $function();
            if ($doEcho) {
                echo $res;
                $view->endCache();
                return false;
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
            if(is_array($this->_variations)){
                $this->_name .= '_' . implode('-', $this->_variations);
            }else{
                $this->_name .= '_' . $this->_variations;
            }
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

    /**
     * Flushes cache by tags
     * @param array $tags
     */
    public static function flushByTags($tags){
        TagDependency::invalidate(\Yii::$app->cache, $tags);
    }

    /**
     * Flushes all, be very caucious, will have a high impact on performance!
     */
    public static function flushForce(){
        Yii::$app->cache->flush();
    }
}