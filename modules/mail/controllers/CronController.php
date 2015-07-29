<?php

namespace app\modules\mail\controllers;

use app\components\Event;
use app\modules\booking\models\Booking;
use app\modules\booking\models\BrainTree;
use app\modules\booking\models\Invoice;
use app\modules\booking\models\Payin;
use app\modules\booking\models\Payout;
use app\modules\mail\models\MailMessage;
use Carbon\Carbon;
use Yii;
use yii\base\Model;

class CronController extends Model
{
    public function minute()
    {
        (new MailMessage())->parseInbox();
    }
}
