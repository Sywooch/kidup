<?php

namespace app\modules\booking\controllers;

use app\controllers\Controller;
use app\modules\booking\models\Booking;
use kartik\mpdf\Pdf;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ViewController extends Controller
{
    public $noFooter = true;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['receipt', 'invoice', 'index'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    private function getPdf(){
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'cssInline' => '.kv-heading-1{font-size:18px}',
        ]);
        return $pdf;
    }
    /**
     * View the receipt of a booking
     * @param $id
     * @return string
     */
    public function actionReceipt($id, $pdf = false)
    {
        $booking = $this->load($id);
        if($booking->renter_id == \Yii::$app->user->id){
            $viewFile = '/renter/receipt';
        }else{
            $viewFile = '/owner/receipt';
        }

        if($pdf){
            $pdf = $this->getPdf();
            $pdf->content = $this->renderPartial($viewFile,
                [
                    'booking' => $booking,
                    'item' => $booking->item
                ]);
            return $pdf->render();
        }
        return $this->render($viewFile, [
            'booking' => $booking,
            'item' => $booking->item
        ]);
    }

    /**
     * View the invoice of a booking
     * @param $id
     * @return string
     */
    public function actionInvoice($id, $pdf = false)
    {
        $booking = $this->load($id);

        if($booking->renter_id == \Yii::$app->user->id){
            $viewFile = '/renter/invoice';
            if($booking->payin->invoice_id == null){
                \Yii::$app->session->addFlash('error', \Yii::t('booking', 'Invoice is available after the owner accepted the booking.'));
                return $this->redirect('@web/booking/'.$booking->id);
            }
        }else{
            $viewFile = '/owner/invoice';
            if(is_null($booking->payout) || $booking->payout->invoice_id == null){
                \Yii::$app->session->addFlash('error', \Yii::t('booking', 'Invoice is available 24h after the booking started.'));
                return $this->redirect('@web/booking/'.$booking->id);
            }
        }

        $invoice = $booking->payin->invoice;
        $invoice['data'] = Json::decode($booking->payin->invoice->data);
        if($pdf){
            $pdf = $this->getPdf();
            $pdf->content = $this->renderPartial($viewFile, ['invoice' => $invoice->getAttributes()]);
            return $pdf->render();
        }

        return $this->render($viewFile, ['invoice' => $invoice->getAttributes()]);
    }

    /**
     * View a single booking
     * @param $id
     * @return string
     */
    public function actionIndex($id, $pdf = false)
    {
        $this->noFooter = false;
        $booking = $this->load($id);
        if($booking->renter_id == \Yii::$app->user->id){
            $viewFile = '/renter/view';
        }else{
            $viewFile = '/owner/view';
        }


        if($pdf){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $pdf = $this->getPdf();
            $pdf->content = $this->renderPartial($viewFile,
                [
                    'booking' => $booking,
                    'item' => $booking->item
                ]);
            return $pdf->render();
        }

        return $this->render($viewFile, [
            'booking' => $booking,
            'item' => $booking->item
        ]);

    }

    private function load($id)
    {
        $booking = Booking::findOne($id);
        if($booking == null){
            throw new NotFoundHttpException("Not found");
        }
        if($booking->renter_id !== \Yii::$app->user->id && $booking->item->owner_id != \Yii::$app->user->id){
            throw new ForbiddenHttpException("Not your booking");
        }
        if($booking->status == Booking::AWAITING_PAYMENT){
            if(\Yii::$app->user->id == $booking->renter_id){
                return $this->redirect('@web/booking/'.$id.'/confirm');
            }else{
                \Yii::$app->session->addFlash('info', \Yii::t('booking', 'The renter has yet to confirm the booking'));
                return $this->redirect('@web/booking/current');
            }
        }
        return $booking;
    }
}
