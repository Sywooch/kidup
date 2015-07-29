<?php

namespace app\modules\review\forms;

use yii\base\Model;
use app\modules\review\models\Review;

class OwnerReview extends Model
{
    public $booking;

    public $public;
    public $private;
    public $kidupPrivate;

    public $communication;
    public $exchange;
    public $handling;

    public function formName()
    {
        return 'owner-review';
    }

    public function rules()
    {
        return [
            [['public'], 'required'],
            [['public'], 'string', 'min' => 15],
            [['public', 'private', 'kidupPrivate'], 'string'],
            [['communication', 'exchange', 'handling'], 'integer', 'min' => 0, 'max' => 5],
        ];
    }

    public function save(){
        if(!$this->validate()) return false;

        Review::create(Review::TYPE_USER_PUBLIC, $this->public, $this->booking, true);

        Review::create(Review::TYPE_USER_PRIVATE, $this->private, $this->booking, true);
        Review::create(Review::TYPE_PRIVATE_KIDUP, $this->kidupPrivate, $this->booking, true);
        Review::create(Review::TYPE_USER_COMMUNICATION, $this->communication, $this->booking, true);
        Review::create(Review::TYPE_USER_HANDLING, $this->handling, $this->booking, true);
        Review::create(Review::TYPE_USER_EXCHANGE, $this->exchange, $this->booking, true);

        (new Review())->checkForPublication($this->booking);

        return true;
    }
}