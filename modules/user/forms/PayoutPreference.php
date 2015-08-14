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

use app\components\Encrypter;
use app\modules\user\models\Country;
use app\modules\user\models\PayoutMethod;
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
    public $identifier_1_encrypted;
    public $identifier_2;
    public $identifier_2_encrypted;

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
        $this->identifier_1_encrypted = '';
        $this->identifier_2_encrypted = '';

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
            [['identifier_1_encrypted', 'identifier_2_encrypted', 'payee_name'], 'required'],
            [['identifier_1_encrypted'], 'integer', 'max' => 9999999999],
            [['identifier_2_encrypted'], 'integer', 'max' => 9999],
            [['bank_name', 'payee_name', 'identifier_1','identifier_2'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'identifier_1_encrypted' => \Yii::t('user', 'Konto Number'),
            'identifier_2_encrypted' => \Yii::t('user', 'Bank Number'),
            'bank_name' => \Yii::t('user', 'Bank Name'),
            'payee_name' => \Yii::t('user', 'Payee Name'),
        ];
    }

    private function transformToSafe($input, $leaveUntouched = 4){
        $length = strlen($input);
        $input = substr($input, $length - $leaveUntouched);
        return str_repeat("*",  $length - $leaveUntouched).$input;
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

            $method->identifier_1 = $this->transformToSafe($this->identifier_1_encrypted, 4);
            $method->identifier_1_encrypted = \app\components\Encrypter::encrypt($this->identifier_1_encrypted, Encrypter::SIZE_1024);
            $method->identifier_2 = $this->transformToSafe($this->identifier_2_encrypted, 2);
            $method->identifier_2_encrypted = \app\components\Encrypter::encrypt($this->identifier_2_encrypted, Encrypter::SIZE_1024);

            if($method->save()){
                return true;
            }else{
                \Yii::error(Json::encode($method->getErrors()));
            }
        }

        return false;
    }
}
