<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com//>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace notification\models;

use booking\models\booking\Booking;
use item\models\item\Item;
use user\models\user\User;
use yii\helpers\Url;

/**
 * URL factory which can create URLs to common endpoints.
 *
 * @package mail\models
 */
class UrlFactory
{
    public static function profile(User $user)
    {
        return self::url("user/" . $user->id);
    }

    public static function search()
    {
        return self::url("search");
    }

    public static function website()
    {
        return self::url("home");
    }

    public static function seeInBrowser($id)
    {
        return self::url("mail/" . $id);
    }

    public static function changeSettings()
    {
        return self::url('user/settings');
    }

    public static function review(Booking $booking)
    {
        return self::url('review/create/' . $booking->id);
    }

    public static function itemCompletion(item\Item $item)
    {
        return self::url("item/edit-basics?id=" . $item->id);
    }

    public static function chat(\message\models\conversation\Conversation $conversation) {
        return self::url('messages/' . $conversation->id);
    }

    public static function help() {
        return self::url("help");
    }

    public static function contact() {
        return self::url("contact");
    }

    public static function receipt(Booking $booking) {
        return self::url('booking/' . $booking->id . '/receipt');
    }

    public static function booking(Booking $booking) {
        return self::url('booking/' . $booking->id);
    }

    public static function url($to)
    {
        return Url::to("@web/" . $to, [
            'mail_id' => 'x'
        ], true);
    }

}