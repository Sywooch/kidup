<?php

namespace codecept\_support;

use codecept\muffins\Booking;
use codecept\muffins\Conversation;
use codecept\muffins\Currency;
use codecept\muffins\Invoice;
use codecept\muffins\Item;
use codecept\muffins\Location;
use codecept\muffins\Media;
use codecept\muffins\Message;
use codecept\muffins\OauthAccessToken;
use codecept\muffins\OauthClient;
use codecept\muffins\Payin;
use codecept\muffins\Payout;
use codecept\muffins\Profile;
use codecept\muffins\Token;
use codecept\muffins\User;
use Codeception\Module;
use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Stores\ModelStore;

/**
 * Class FactoryMuffin
 * @var FactoryMuffin $factory
 * @package common\components
 */
class MuffinHelper
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
        static::$factory = new FactoryMuffin(new ModelStore('save', 'delete'));
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
        return self::$factory;
    }

}