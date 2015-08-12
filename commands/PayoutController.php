<?php

namespace app\commands;

use app\backup\Database;
use app\backup\File;
use app\components\Encrypter;
use app\components\Event;
use app\modules\booking\models\Payin;
use app\modules\mail\mails\User;
use app\modules\mail\models\MailMessage;
use app\modules\mail\models\Token;
use app\modules\user\models\PayoutMethod;
use yii\console\Controller;
use Yii;
use app\modules\images\components\ImageManager;

class PayoutController extends Controller
{
    public function actionExport(){
        $fileName = '/vagrant/test.csv';

        if(!is_file($fileName)){
            echo 'not a file';exit();
        }
        $file = file_get_contents($fileName);
        $lines = explode(PHP_EOL, $file);
        foreach ($lines as &$payout) {
            $numbs = explode('","', $payout);
            $first_ref = Encrypter::decrypt($numbs[83]);
            // last one is a " because the explode method
            $second_ref = Encrypter::decrypt(substr($numbs[84], 0, strlen($numbs[84])));
            $numbs[2] = $second_ref.''.$first_ref; // make sure it concats as a string
            unset($numbs[83]);
            unset($numbs[84]);
            $payout = implode('","', $numbs);
            // remove comma at the end
            $payout = substr($payout, 0, strlen($payout)-1);
        }
        $file = implode(PHP_EOL, $lines);
        file_put_contents($fileName.'.exp', $file);
    }
}
