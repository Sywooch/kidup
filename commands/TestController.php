<?php

namespace app\commands;

use app\backup\Database;
use app\backup\File;
use app\components\Event;
use app\modules\booking\models\Payin;
use app\modules\mail\mails\User;
use app\modules\mail\models\MailMessage;
use app\modules\mail\models\Token;
use app\modules\user\models\PayoutMethod;
use Carbon\Carbon;
use yii\console\Controller;
use Yii;
use app\modules\images\components\ImageManager;

class TestController extends Controller
{
    public function actionEmail(){
        $user = \app\modules\user\models\User::find()->one();
        Event::trigger($user, \app\modules\user\models\User::EVENT_USER_REGISTER_DONE);
    }

    public function actionMail(){
        $mm = new MailMessage();
        $mm->setAttributes([
            'message' => 'test',
            'from_email' => 'test',
            'subject' => 'test',
            'created_at' => time(),
            'mail_acount_name' => '83c2c3n23c',
            'message_id' => 2
        ]);

        $mm->mail_account_name = '7bZfplYnflxLxhc7W9iNNrq2EQUHhaEV';

        $mm->save();
        
        var_dump($mm->getErrors(),10,true); exit();
    }

    public function actionTest(){
        $k = new \Aws\Kms\KmsClient([
            'region'            => 'us-east-1',
            'version'           => '2014-11-01',
            'credentials' => [
                'key' => 'AKIAJOPRJWDPXZUWVU4A',
                'secret' => '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',
            ]
        ]);
        var_dump($k->encrypt([
            'KeyId' => '1aeaff2a-acff-48c3-add8-0b7ee54407f0',
            'Plaintext' => 'EncryptMeUp!'
        ]));
    }
}
