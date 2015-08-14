<?php

namespace app\components;

use yii\base\Component;

class KeyStore extends Component
{
    private $keys;

    public function __construct()
    {
        $keyFile = __DIR__ . '/../config/keys/keys.env';
        if (is_file($keyFile)) {
            $keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();
            $this->keys = $keys;
        }
        return parent::__construct();
    }

    public function get($key)
    {
        if(isset($this->keys[$key])){
            return $this->keys[$key];
        }
        return true;
    }
}