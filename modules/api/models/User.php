<?php

namespace api\models;

use images\components\ImageHelper;
use review\models\Review;

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
            'phone_number' => function () {
                return $this->profile->getPhoneNumber();
            },
            'language' => function () {
                return $this->profile->language;
            },
            'review_score' => function () {
                return (new Review())->computeOverallUserScore($this);
            },
            'created_at' => function () {
                return $this->created_at;
            },
            'can_accept_booking' => function () {
                return $this->canAcceptBooking();
            }
        ];
        if (strpos(\Yii::$app->request->getQueryParam('expand'), 'items') !== false) {
            $fields['items'] = function ($model) {
                return Item::find()->where([
                    'owner_id' => $model->id,
                    'is_available' => 1,
                ])->orderBy('created_at DESC')->all();
            };
        }

        if (\Yii::$app->user->isGuest || ($this->id !== \Yii::$app->user->id && !$this->allowPrivateAttributes(\Yii::$app->user->identity))) {
            foreach (['email', 'phone_number', 'language'] as $item) {
                unset($fields[$item]);
            }
        }

        if ($this->id !== \Yii::$app->user->id) {
            foreach (['can_accept_booking'] as $item) {
                unset($fields[$item]);
            }
        }
        return $fields;
    }

    public function extraFields()
    {
        return ['item'];
    }

    public function getItems()
    {
        return $this->hasOne(Item::className(), ['id' => 'owner_id']);
    }
}
