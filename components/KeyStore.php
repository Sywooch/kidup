<?php

namespace app\components;

use yii\base\Component;

class KeyStore extends Component
{
    private $keys;

    public $enable_analytics = false;
    public $yii_cache = false;
    public $fake_products = false;

    public function __construct()
    {
        $keyFile = __DIR__ . '/../config/keys/keys.env';
        if (is_file($keyFile)) {
            $keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();
            $this->keys = $keys;
            foreach ($this->keys as $key => $val) {
                if(property_exists(KeyStore::className(), $key)){
                    $this->{$key} = $val;
                }
            }
        }
        return parent::__construct();
    }

    public function get($key)
    {
        if(isset($this->keys[$key])){
            return $this->keys[$key];
        }
        return false;
    }
}