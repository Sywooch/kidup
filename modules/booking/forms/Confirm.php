<?php

namespace booking\forms;

use \booking\models\Booking;
use \booking\models\BrainTree;
use \booking\models\Payin;
use yii\base\Model;

class Confirm extends Model
{

    public $booking;
    public $message;
    public $rules;
    public $nonce;

    public function formName()
    {
        return 'confirm-booking';
    }

    public function attributeLabels()
    {
        return [
            'rules' => \Yii::t('booking.create.nonce_required',
                "I agree to the terms and conditions, and verify that my card is charged with {0} DKK", [
                    $this->booking->amount_payin
                ])
        ];
    }

    public function rules()
    {
        return [
            [['message'], 'string'],
            [['rules', 'booking'], 'required'],
            [
                'nonce',
                'required',
                'message' => \Yii::t('booking.create.invalid_payment_method', 'Please add a valid payment method.')
            ],
            [
                'rules',
                'compare',
                'compareValue' => true,
                'message' => \Yii::t('booking.create.agree_to_terms', 'You must agree to the terms and conditions')
            ],

        ];
    }

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        return parent::__construct();
    }

    public function save()
    {
        if (YII_ENV == 'test') {
            $this->nonce = 'fake-valid-nonce';
        }
        if (!$this->validate()) {
            return false;
        };

        $payin = new Payin();
        $payin->nonce = $this->nonce;
        $payin->status = Payin::STATUS_INIT;
        $payin->currency_id = 1;
        $payin->user_id = \Yii::$app->user->id;
        $payin->amount = $this->booking->amount_payin;

        if ($payin->save()) {
            $this->booking->save();
            // need to set this beore authorize function
            $this->booking->payin_id = $payin->id;
            $this->booking->startConversation($this->message);
            $this->booking->save();

            if ($payin->authorize()) {
                $this->booking->status = Booking::PENDING;
                $this->booking->request_expires_at = time() + 48 * 60 * 60;

                if ($this->booking->save()) {
                    \Yii::$app->session->addFlash('success',
                        \Yii::t('booking.flash.successfully_created', "You're booking has been made!"));
                    return true;
                }
            }
        }
        return false;
    }
}