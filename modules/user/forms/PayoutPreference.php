<?php

/*
 * This file is part of the app\modules project.
 *
 * (c) app\modules project <http://github.com/app\modules/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\user\forms;

use app\modules\user\models\PayoutMethod;
use app\modules\user\models\Country;
use app\modules\user\models\User;
use yii\base\Model;
use yii\helpers\Json;

class PayoutPreference extends Model
{

    public $address;
    public $bank_name;
    public $payee_name;
    public $country_id;
    public $type;
    public $identifier_1;
    public $identifier_2;

    private $method;

    public function __construct()
    {
        $this->method = PayoutMethod::find()->where(['user_id' => \Yii::$app->user->id])->one();
        if ($this->method !== null) {
            $this->setAttributes(Json::decode(Json::encode($this->method)));
        } else {
            $this->country_id = Country::findOne(1)->id; // denmark for now
            $this->type = PayoutMethod::TYPE_DK_KONTO;
        }

        return parent::__construct();
    }

    public static function getUser($id)
    {

    }

    /** @inheritdoc */
    public function formName()
    {
        return 'payout-preferences-form';
    }

    public function rules()
    {
        return [
            [['identifier_1', 'identifier_2', 'payee_name'], 'required'],
            [['identifier_1'], 'integer', 'max' => 9999999999],
            [['identifier_2'], 'integer', 'max' => 9999],
            [['bank_name', 'payee_name'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'identifier_1' => \Yii::t('user', 'Konto Number'),
            'identifier_2' => \Yii::t('user', 'Bank Number'),
            'bank_name' => \Yii::t('user', 'Bank Name'),
            'payee_name' => \Yii::t('user', 'Payee Name'),
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            if (!isset($this->method)) {
                $method = new PayoutMethod();
                $method->user_id = \Yii::$app->user->id;
                $method->type = PayoutMethod::TYPE_DK_KONTO;
                $method->country_id = $this->country_id;
            } else {
                $method = $this->method;
            }
            $method->bank_name = 'unknown';
            $method->payee_name = $this->payee_name;
            $method->identifier_1 = $this->identifier_1;
            $method->identifier_2 = $this->identifier_2;

            if($method->save()){
                return true;
            }else{
                \Yii::error(Json::encode($method->getErrors()));
            }
        }

        return false;
    }
}
