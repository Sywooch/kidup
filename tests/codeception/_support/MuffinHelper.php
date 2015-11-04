<?php

namespace tests\_support;

use tests\muffins\Booking;
use tests\muffins\Conversation;
use tests\muffins\Currency;
use tests\muffins\Invoice;
use tests\muffins\Item;
use tests\muffins\Location;
use tests\muffins\Media;
use tests\muffins\Message;
use tests\muffins\OauthAccessToken;
use tests\muffins\OauthClient;
use tests\muffins\Payin;
use tests\muffins\Payout;
use tests\muffins\Profile;
use tests\muffins\Token;
use tests\muffins\User;
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
            Message::class,
            Media::class,
            OauthAccessToken::class,
            OauthClient::class,
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