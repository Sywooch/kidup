<?php

namespace app\tests\codeception\_support;

use app\tests\codeception\muffins\Booking;
use app\tests\codeception\muffins\Conversation;
use app\tests\codeception\muffins\Currency;
use app\tests\codeception\muffins\Invoice;
use app\tests\codeception\muffins\Item;
use app\tests\codeception\muffins\Location;
use app\tests\codeception\muffins\Message;
use app\tests\codeception\muffins\Payin;
use app\tests\codeception\muffins\Payout;
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
    public static $factory;

    /**
     * Get a list with all muffin classes.
     *
     * @return array list with all muffin classes
     */
    public static function getClasses()
    {
        return [
            Booking::class,
            Currency::class,
            Invoice::class,
            Item::class,
            Payin::class,
            Payout::class,
            Profile::class,
            Token::class,
            User::class,
            Location::class,
            Conversation::class,
            Message::class
        ];
    }

    /**
     * Method called before any suite tests run. Loads User fixture login user
     * to use in acceptance and functional tests.
     * @return FactoryMuffin
     */
    public function init()
    {
        static::$factory = new FactoryMuffin();
        foreach (self::getClasses() as $model) {
            $defs = $model::definitions();
            if (method_exists($model, 'callback')) {
                static::$factory->define($model)->setDefinitions($defs)->setCallback(function ($object, $saved) use ($model) {
                    return $model::callback($object, $saved);
                });
            } else {
                static::$factory->define($model)->setDefinitions($defs);
            }
        }
        static::$factory->setSaveMethod('save')->setDeleteMethod('delete');
        return self::$factory;
    }

    /**
     * Method is called after all suite tests run
     */
    public function _after(\Codeception\TestCase $test)
    {
        //static::$factory->deleteSaved();
    }

}