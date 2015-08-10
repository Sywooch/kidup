<?php

namespace app\commands;

use app\backup\Database;
use app\backup\File;
use app\components\Event;
use app\modules\booking\models\Payin;
use app\modules\mail\mails\User;
use app\modules\mail\models\Token;
use app\modules\user\models\PayoutMethod;
use yii\console\Controller;
use Yii;
use app\modules\images\components\ImageManager;

class TestController extends Controller
{
    public function actionToS3()
    {
        $dir = scandir('/vagrant/users/org');
        foreach ($dir as $d) {
            if (strpos($d, ".png") == false && strpos($d, ".jpg") == false) {
                continue;
            }
            $o = $d;
            $d = str_replace("_original", '', $d);
            $d = explode("_", $d)[2];
            $filename = $d;
            $dir = '';
            $dir .= 'user-uploads/' . ImageManager::createSubFolders($filename);
            $i = new ImageManager();
            $i->filesystem->createDir($dir);
            $i->filesystem->write($dir . '/' . $d, file_get_contents('/vagrant/users/org/'.$o));
        }

    }

    public function actionEmail(){
        $user = \app\modules\user\models\User::find()->one();
        Event::trigger($user, \app\modules\user\models\User::EVENT_USER_REGISTER_DONE);
    }

    public function actionExport(){
        $field = [];
        $field[1] = 'CMBOD';
        $field[2] = '11658814'; // kidup account
        $payoutMethod = PayoutMethod::find()->one();
        $field[3] = $payoutMethod->identifier_2 . '+' . $payoutMethod->identifier_1; // to account
        $field[4] = 1.23; // to account
        $field[20] = "KidUp Payout ".$this->id;

        $str = [];
        for($i = 1; $i < 76; $i++){
            if(!isset($field[$i])){
                $str[] = '';
            }else{
                $str[] = $field[$i];
            }
        }

        echo '"'.implode('","', $str).'",';
    }
}
