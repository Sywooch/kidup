<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com//>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mail\models;

use booking\models\Booking;
use Carbon\Carbon;
use item\models\Item;
use user\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;

class UrlFactory
{
    public static function profile(User $user){
        return self::url("user/".$user->id);
    }

    public static function search(){
        return self::url("search");
    }

    public static function seeInBrowser($id){
        return self::url("view/".$id);
    }

    public static function changeSettings(){
        return self::url('user/settings');
    }

    public static function review(Booking $booking){
        return self::url('review/create/'.$booking->id);
    }

    public static function itemCompletion(Item $item){
        return self::url("item/edit-basics?id=".$item->id);
    }

    public static function url($to){
        return Url::to("@web/".$to, [
            'mail_id' => 'x'
        ], true);
    }
}