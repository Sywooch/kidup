<?php

namespace app\tests\codeception\_support;

use League\FactoryMuffin\Facade as FactoryMuffin;
use app\models\base\User;

class FactoryHelper extends \Codeception\Module
{
    /**
     * @var \League\FactoryMuffin\Factory
     */
    protected $factory;

    public function _initialize()
    {
        FactoryMuffin::define('\app\models\base\User', array(
            'email' => 'unique:email', // random unique email
        ));

        FactoryMuffin::create('\app\models\base\User');

//        $this->factory->seed(1, 'User');
    }
}
?>