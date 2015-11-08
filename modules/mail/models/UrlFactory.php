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

use Carbon\Carbon;
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

    public static function url($to){
        return Url::to("@web/".$to, [
            'mail_id' => 'x'
        ]);
    }
}