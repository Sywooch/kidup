<?php

namespace api\models;

use images\components\ImageHelper;

/**
 * This is the model class for table "item".
 */
class User extends \user\models\User
{
    public function isUser($model, $prop){
        if($model->id === \Yii::$app->user->id){
            return $model->profile->{$prop};
        }
        return '';
    }

    public function fields()
    {
        return [
            'id',
            'description' => function ($model) {
                return $model->profile->description;
            },
            'first_name' => function ($model) {
                return $model->profile->first_name;
            },
            'last_name' => function ($model) {
                return $model->profile->last_name;
            },
            'email_verified' => function ($model) {
                return (bool)$model->profile->email_verified;
            },
            'phone_verified' => function ($model) {
                return (bool)$model->profile->phone_verified;
            },
            'img' => function ($model) {
                return ImageHelper::urlSet($model->profile->getAttribute('img'), true);
            },
            'email' => function ($model) {
                return $model->id === \Yii::$app->user->id ? $model->email : '';
            },
            'phone_country' => function ($model) {
                return $this->isUser($model, 'phone_country');
            },
            'phone_number' => function ($model) {
                return $this->isUser($model, 'phone_number');
            },
            'language' => function ($model) {
                return $this->isUser($model, 'language');
            }
        ];
    }
}
