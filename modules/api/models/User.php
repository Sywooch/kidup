<?php

namespace api\models;

use images\components\ImageHelper;

/**
 * This is the model class for table "item".
 */
class User extends \user\models\User
{
    public function fields()
    {
        $fields = [
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
                return $model->email;
            },
            'phone_number' => function ($model) {
                return $this->profile->getPhoneNumber();
            },
            'language' => function ($model) {
                return $this->profile->language;
            },
            'review_score' => function () {
                return 5;
            }
        ];

        if (\Yii::$app->user->isGuest || ($this->id !== \Yii::$app->user->id && !$this->allowPrivateAttributes(\Yii::$app->user->identity))) {
            foreach (['email', 'phone_number', 'language'] as $item) {
                unset($fields[$item]);
            }
        }
        return $fields;
    }
}
