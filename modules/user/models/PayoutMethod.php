<?php

namespace user\models;

use Carbon\Carbon;
use Yii;

class PayoutMethod extends \user\models\base\PayoutMethod
{
    const TYPE_DK_KONTO = 'dk-konto';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'payee_name',
                    'country_id',
                    'type',
                    'user_id',
                    'identifier_1',
                    'identifier_2',
                    'created_at',
                    'updated_at'
                ],
                'required'
            ],
            [['country_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['type', 'identifier_1', 'identifier_2'], 'string', 'max' => 45],
            [['payee_name'], 'string', 'max' => 256]
        ];
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
        }
        $this->updated_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
        return parent::beforeValidate();
    }

    public function userHasAccess($user = false){
        $user = $user ? $user : \Yii::$app->user;
        return $user->id === $this->user_id;
    }
}
