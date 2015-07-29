<?php

namespace app\modules\review\forms;

use yii\base\Model;
use app\modules\message\models\Message;
use app\modules\review\models\Review;
class RenterReview extends Model
{
    public $booking;

    public $public;
    public $private;
    public $kidupPrivate;

    public $communication;
    public $exchange;
    public $experience;
    public $adAccuracy;

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
            [['communication', 'exchange', 'adAccuracy'], 'integer', 'min' => 0, 'max' => 5],
            [['experience'], 'integer', 'min' => 1, 'max' => 5],
        ];
    }

    public function save(){
        if(!$this->validate()) return false;

        Review::create(Review::TYPE_USER_PUBLIC, $this->public, $this->booking, false);
        Review::create(Review::TYPE_EXPERIENCE, $this->experience, $this->booking, false);

        Review::create(Review::TYPE_USER_PRIVATE, $this->private, $this->booking, false);
        Review::create(Review::TYPE_PRIVATE_KIDUP, $this->kidupPrivate, $this->booking, false);
        Review::create(Review::TYPE_USER_COMMUNICATION, $this->communication, $this->booking, false);
        Review::create(Review::TYPE_USER_EXCHANGE, $this->exchange, $this->booking, false);
        Review::create(Review::TYPE_AD_ACCURACY, $this->adAccuracy, $this->booking, false);

        (new Review())->checkForPublication($this->booking);

        return true;
    }

}