<?php

namespace app\modules\booking\forms;

use app\modules\booking\models\Booking;
use app\modules\booking\models\BrainTree;
use app\modules\booking\models\Payin;
use yii\base\Model;

class Confirm extends Model
{

    public $booking;
    public $message;
    public $rules;
    public $nonce;

    private $creditCardForm;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $payin = Payin::findOne($this->booking->payin_id);
        if ($payin !== null && $payin->nonce != '') {
            $this->creditCardForm = 'creditcard_nonce';
        } elseif (isset($_POST["payment_method_nonce"]) && $_POST["payment_method_nonce"] != '') {
            $this->createPayin($_POST["payment_method_nonce"]);
            $this->creditCardForm = 'creditcard_nonce';
        }

        if (!isset($this->creditCardForm)) {
            $this->creditCardForm = 'creditcard';
        }

        return parent::__construct();
    }

    private function createPayin($nonce)
    {
        if ($this->booking->payin_id !== null) {
            $payin = $this->booking->payin;
        } else {
            $payin = new Payin();
        }
        $payin->nonce = $nonce;
        $payin->status = 'init';
        $payin->currency_id = 1;
        $payin->user_id = \Yii::$app->user->id;
        $payin->amount = $this->booking->amount_payin;
        if ($payin->save()) {
            $this->booking->payin_id = $payin->id;
            $this->booking->save();

            return true;
        }

        return false;
    }

    public function formName()
    {
        return 'confirm-booking';
    }

    public function attributeLabels()
    {
        return [
            'rules' => \Yii::t('booking',
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
            ['nonce', 'required', 'message' => \Yii::t('booking', 'Please add a creditcard')],
            [
                'rules',
                'compare',
                'compareValue' => true,
                'message' => \Yii::t('booking', 'You must agree to the terms and conditions')
            ],
        ];
    }

    public function renderCreditCardForm()
    {

        if ($this->creditCardForm == 'creditcard') {
            $b = new BrainTree(new Payin());
            $token = $b->getClientToken();
        } else {
            $token = '';
        }

        return \Yii::$app->controller->renderPartial($this->creditCardForm, [
            'clientToken' => $token
        ]);
    }

    public function save()
    {
        $payin = Payin::findOne($this->booking->payin_id);
        if (count($payin) > 0) {
            $this->nonce = $payin->nonce;
        }
        if ($this->validate()) {
            if ($payin->authorize()) {
                $this->booking->status = Booking::PENDING;
                $this->booking->startConversation($this->message);
                $this->booking->request_expires_at = time() + 48 * 60 * 60;

                if ($this->booking->save()) {
                    \Yii::$app->session->addFlash('success', \Yii::t('booking', "You're booking has been made!"));
                    return true;
                }
            } else {
                return false;
            }
        } else {
            \Yii::$app->session->addFlash('error', \Yii::t('booking', 'You must agree to the terms and conditions'));
        }

        return false;
    }
}