<?php

namespace app\tests\codeception\_support;

use app\tests\codeception\muffins\Item;
use app\tests\codeception\muffins\Profile;
use app\tests\codeception\muffins\Token;
use app\tests\codeception\muffins\User;
use Codeception\Util\Debug;
use League\FactoryMuffin\Exceptions\ModelException;
use League\FactoryMuffin\Exceptions\ModelNotFoundException;
use Codeception\Module;
use League\FactoryMuffin\FactoryMuffin;
/**
 * Class FactoryMuffin
 * @var FactoryMuffin $factory
 * @package common\components
 */
class MuffinHelper extends Module
{
    public $factory;

    /**
     * Method called before any suite tests run. Loads User fixture login user
     * to use in acceptance and functional tests.
     * @param array $settings
     */
    public function init()
    {
        $factory = new FactoryMuffin();
        $classes = [
            User::class,
            Token::class,
            Profile::class,
            Item::class
        ];

        foreach ($classes as $model) {
            $defs = $model::definitions();
            $factory->define($model)->setDefinitions($defs);
            Debug::debug($model);
        }
        $factory->setSaveMethod('save')->setDeleteMethod('delete');
        $this->factory = $factory;
        return $this;
    }

    /**
     * Method is called after all suite tests run
     */
    public function _after(\Codeception\TestCase $test)
    {
//        $this->factory->deleteSaved();
    }

}