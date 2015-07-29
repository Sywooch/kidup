<?php

namespace app\modules\user\helpers;

use yii\base\Model;
use app\modules\user\models\Country;
use app\modules\user\models\Language;
use app\models\base\Currency;

class SelectData extends Model{

    public static function nationality(){
        $c = Country::find()->all();
        $r= [];
        foreach($c as $country){
            $r[$country->id] = $country->name;
        }
        return $r;
    }

    public static function phoneCountries(){
        $c = Country::find()->all();
        $r= [];
        foreach($c as $country){
            $r[$country->phone_prefix] = $country->name . '(+'.$country->phone_prefix.')';
        }
        return $r;
    }
    public static function languages(){
        $c = Language::find()->where(['status' => 1])->all();
        $r= [];
        foreach($c as $lang){
            $r[$lang->language_id] = $lang->name;
        }
        return $r;
    }
    public static function currencies(){
        $c = Currency::find()->all();
        $r= [];
        foreach($c as $cur){
            $r[$cur->id] = $cur->name;
        }
        return $r;
    }
}