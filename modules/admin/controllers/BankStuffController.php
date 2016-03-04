<?php

namespace admin\controllers;

use admin\forms\UploadKey;
use app\helpers\Encrypter;
use booking\models\payout\PayoutBase;
use Yii;
use yii\web\UploadedFile;

class BankStuffController extends Controller
{
    public function actionIndex()
    {
        $model = new UploadKey();

        if (Yii::$app->request->isPost) {
            $model->keyFile = UploadedFile::getInstance($model, 'keyFile');
            $key = file_get_contents($model->keyFile->tempName);
            $payouts = PayoutBase::findAll(['status' => PayoutBase::STATUS_TO_BE_PROCESSED]);
            if (count($payouts) == 0) {
                echo 'nothing to pay';
                exit();
            }
            $export = [];
            foreach ($payouts as $p) {
                $export[] = $p->exportDankseBank($key);
            }

            $e = implode(PHP_EOL, $export);
            $enCrypted = Encrypter::encrypt($e);
            file_put_contents(\Yii::$aliases['@runtime'] . '/payout-export-' . time(), $enCrypted);
            $res  = Encrypter::decrypt($enCrypted, $key);
            $time = time();
            foreach ($payouts as $p) {
                $p->markAsProcessed();
            }
            echo nl2br($e);exit();
        }

        return $this->render('export', ['model' => $model]);
    }
}
