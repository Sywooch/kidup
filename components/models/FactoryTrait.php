<?php

namespace app\components\models;

use yii\web\BadRequestHttpException;

trait FactoryTrait
{
    public function create()
    {
        if(!$this->validate()){
            return $this;
        }
        $this->save();
        return $this;
    }

    public function createForApi($params, $apiObjectClassname){
        $res = $this->load($params, '');
        $obj = $this->create();
        if($this->hasErrors()){
            return $this;
        }
        $x = \Yii::createObject($apiObjectClassname, $obj->attributes);
        $x->setAttributes($obj->attributes);

        return $x;
    }
}