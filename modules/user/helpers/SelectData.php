<?php

namespace app\modules\user\helpers;

use app\models\base\Currency;
use app\modules\item\models\Location;
use app\modules\user\models\Country;
use app\modules\user\models\Language;
use yii\base\Model;

class SelectData extends Model
{

    public static function nationality()
    {
        $c = Country::find()->all();
        $r = [];
        foreach ($c as $country) {
            $r[$country->id] = $country->name;
        }
        return $r;
    }

    public static function phoneCountries()
    {
        $c = Country::find()->all();
        $r = [];
        foreach ($c as $country) {
            $r[$country->phone_prefix] = $country->name . '(+' . $country->phone_prefix . ')';
        }
        return $r;
    }

    public static function languages()
    {
        $c = Language::find()->where(['status' => 1])->all();
        $r = [];
        foreach ($c as $lang) {
            $r[$lang->language_id] = $lang->name;
        }
        return $r;
    }

    public static function currencies()
    {
        $c = Currency::find()->all();
        $r = [];
        foreach ($c as $cur) {
            $r[$cur->id] = $cur->name;
        }
        return $r;
    }

    public static function userLocations(){
        $l = Location::find()->where(['user_id' => \Yii::$app->user->id])->all();
        $r = [];
        foreach($l as $loc){
            /**
             * @var Location $loc
             */
            if(!$loc->isValid()) continue;
            $address = [$loc->street_name. ' '. $loc->street_number,];
            if(!empty($loc->street_suffix)) $address[] = $loc->street_suffix;
            $address[] = $loc->city;
            $address[] = $loc->country0->name;
            $r[$loc->id] = implode(", ", $address);
        }
        return $r;
    }
}