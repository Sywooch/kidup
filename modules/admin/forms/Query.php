<?php

namespace admin\forms;

use \admin\models\I18nMessage;
use \admin\models\I18nSource;
use booking\models\Booking;
use DeepCopy\DeepCopy;
use item\models\Item;
use user\models\User;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "category".
 */
class Query extends Model
{
    public $entity;
    public $interval;
    public $results;
    public $dateFrom;
    public $dateTo;

    public $entities;
    public $intervals;

    public function init()
    {
        $this->entities = [
            Item::className() => 'item',
            Booking::className() => 'booking',
            User::className() => 'user',
        ];
        $this->intervals = [
            'm' => 'Minute',
            'h' => 'Hour',
            'd' => 'Day',
            'w' => 'Week',
            'month' => 'Month',
        ];
        parent::init();
    }

    public function rules()
    {
        return [
            [['entity'], 'required']
        ];
    }

    public function run()
    {
        if (isset($this->entities[$this->entity])) {
            $query = call_user_func(array($this->entity, 'find'));
            $this->results = [array_keys($query->asArray()->indexBy('id')->all())];
            return true;
        }
        return false;
    }

    public function formName()
    {
        return 'admin-query';
    }
}
