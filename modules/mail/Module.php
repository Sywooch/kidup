<?php

namespace app\modules\mail;

use app\components\Event;
use app\modules\user\models\User;
use Yii;
use app\modules\mail\models\Sender;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\mail\controllers';

    public function init()
    {

    }
}
