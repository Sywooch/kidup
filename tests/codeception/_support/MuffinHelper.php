<?php

namespace codecept\_support;

use codecept\muffins\BookingMuffin;
use codecept\muffins\ConversationMuffin;
use codecept\muffins\CurrencyMuffin;
use codecept\muffins\I18nMessageMuffin;
use codecept\muffins\I18nSourceMuffin;
use codecept\muffins\InvoiceMuffin;
use codecept\muffins\ItemMuffin;
use codecept\muffins\LocationMuffin;
use codecept\muffins\MediaMuffin;
use codecept\muffins\MessageMuffin;
use codecept\muffins\MobileDeviceMuffin;
use codecept\muffins\OauthAccessTokenMuffin;
use codecept\muffins\OauthClientMuffin;
use codecept\muffins\PayinMuffin;
use codecept\muffins\PayoutMethodMuffin;
use codecept\muffins\PayoutMuffin;
use codecept\muffins\ProfileMuffin;
use codecept\muffins\TokenMuffin;
use codecept\muffins\UserMuffin;
use codecept\muffins\WishListItemMuffin;
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
            BookingMuffin::class,
            CurrencyMuffin::class,
            InvoiceMuffin::class,
            ItemMuffin::class,
            PayinMuffin::class,
            PayoutMethodMuffin::class,
            PayoutMuffin::class,
            ProfileMuffin::class,
            TokenMuffin::class,
            UserMuffin::class,
            LocationMuffin::class,
            ConversationMuffin::class,
            MessageMuffin::class,
            MediaMuffin::class,
            OauthAccessTokenMuffin::class,
            OauthClientMuffin::class,
            MobileDeviceMuffin::class,
            WishListItemMuffin::class,
            I18nSourceMuffin::class,
            I18nMessageMuffin::class
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