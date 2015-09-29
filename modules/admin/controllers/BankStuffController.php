<?php

namespace admin\controllers;

use user\models\base\User;
use booking\models\Payout;
use item\models\Item;
use Yii;

class BankStuffController extends Controller
{
    public function actionIndex()
    {
        return $this->render('export');
    }

    public function actionGeneratePayout()
    {
        $payouts = Payout::findAll(['status' => Payout::STATUS_TO_BE_PROCESSED]);
        if (count($payouts) == 0) {
            echo 'nothing to pay';
            exit();
        }
        $export = [];
        foreach ($payouts as $p) {
            $export[] = $p->exportDankseBank();
        }
        $e = implode(PHP_EOL, $export);
        file_put_contents(\Yii::$aliases['@runtime'] . '/payout-export-' . time(), $e);
        echo $e;
        foreach ($payouts as $p) {
            $p->markAsProcessed();
        }
    }

}
