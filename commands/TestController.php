<?php

namespace app\commands;

use app\backup\Database;
use app\backup\File;
use app\components\Event;
use app\modules\booking\models\Payin;
use yii\console\Controller;
use Yii;

class TestController extends Controller
{
    public function actionMail()
    {
//        $u = Payin::find()->orderBy('id DESC')->one();
//        Event::trigger($u, Payin::EVENT_FAILED);
    }
}
