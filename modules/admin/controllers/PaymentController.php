<?php

namespace app\modules\admin\controllers;

use app\controllers\Controller;
use app\modules\booking\models\Payout;
use Yii;

class PaymentController extends Controller
{

    public function actionGeneratePayout()
    {
        $this->checkAdmin();

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

    private function checkAdmin()
    {
        if (\Yii::$app->user->isGuest || !\Yii::$app->user->identity->isAdmin()) {
            return $this->redirect('@web/home');
        }

        return false;
    }
}
