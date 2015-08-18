<?php

namespace app\modules\user\models;

use app\modules\images\components\ImageHelper;
use Yii;
use yii\helpers\ArrayHelper;

class Profile extends \app\models\base\Profile
{
    public $img;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'first_name', 'last_name'], 'required'],
            [
                [
                    'user_id',
                    'email_verified',
                    'phone_verified',
                    'identity_verified',
                    'location_verified',
                    'currency_id',
                    'nationality',
                    'phone_country',
                    'phone_number'
                ],
                'integer'
            ],
            [['description'], 'string'],
            [['first_name'], 'string', 'max' => 128],
            [['last_name', 'img'], 'string', 'max' => 256],
            [['phone_country'], 'string', 'max' => 5],
            [['phone_number'], 'string', 'max' => 50],
            [['language'], 'string', 'max' => 6],
            [['img'], 'safe'],
            [['img'], 'file', 'extensions' => 'jpg, gif, png'],
            [['birthday'], 'date', 'format' => 'dd-mm-yyyy'],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'user_id' => Yii::t('app', 'User ID'),
            'description' => Yii::t('app', 'Profile description'),
            'img' => Yii::t('app', 'Profile image'),
            'phone_country' => Yii::t('app', 'Phone Country'),
            'phone_number' => Yii::t('app', 'Phone Number'),
        ]);

    }

    public function scenarios()
    {
        return ArrayHelper::merge([
            'settings' => ['email', 'language', 'currency_id', 'phone_number', 'phone_country'],
            'phonecode' => ['phone_verified'],
        ], parent::scenarios());
    }

    public function getPhoneNumber()
    {
        if ($this->phone_number == null) {
            return false;
        }
        return $this->phone_country . ' ' . $this->phone_number;
    }

    public function isValidPhoneNumber()
    {
        if (empty($this->phone_number)) {
            return false;
        }
        $number = $this->phone_country . ' ' . $this->phone_number;
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $countries = Country::find()->indexBy('phone_prefix')->all();
        try {
            $numberProto = $phoneUtil->parse($number, $countries[$this->phone_country]->code);
            return $phoneUtil->isValidNumber($numberProto);
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }

    public function sendPhoneVerification($token)
    {
        $message = urlencode('KidUp code ' . $token->code);
        $key = Yii::$app->keyStore->get('nexmo_api_key');
        $secret = Yii::$app->keyStore->get('nexmo_api_secret');
        $url = 'https://rest.nexmo.com/sms/json?api_key=' . $key . '&api_secret=' . $secret . '&from=KidUp&to=+' . $this->phone_country . $this->phone_number . '&text=' . $message;
        $res = json_decode(file_get_contents($url));
        if (isset($res->messages[0]->{"error-text"})) {
            \Yii::$app->session->setFlash('error', 'Error while sending text: ' . $res->messages[0]->{"error-text"});
            return false;
        }
        return true;
    }

    public function getImgUrl()
    {
        return ImageHelper::url($this->getAttribute('img'), ['q' => 90, 'w' => 300]);
    }

    public function beforeSave($insert)
    {
        $this->birthday = strtotime($this->birthday);
        if ($this->isAttributeChanged('language') && \Yii::$app->has('session')) {
            Yii::$app->session->set('lang', $this->language);
        }
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->birthday = date('d-m-Y', $this->birthday);
        return parent::afterFind();
    }

    public function beforeValidate()
    {
        if ($this->isAttributeChanged('description')) {
            $this->description = \yii\helpers\HtmlPurifier::process($this->description);
        }
        if ($this->isAttributeChanged('first_name')) {
            $this->first_name = \yii\helpers\HtmlPurifier::process($this->first_name);
        }
        if ($this->isAttributeChanged('last_name')) {
            $this->last_name = \yii\helpers\HtmlPurifier::process($this->last_name);
        }
        return parent::beforeValidate();
    }
}
