<?php
namespace api\controllers;

use api\models\PayoutMethod;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class PayoutMethodController extends Controller
{
    public function init()
    {
        $this->modelClass = PayoutMethod::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => [],
            'user' => ['create', 'view', 'index', 'delete', 'update']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();

        // overwrite the default create action
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['delete']);
        unset($actions['update']);

        return $actions;
    }

    public function actionIndex()
    {
        return PayoutMethod::find()->where([
            'user_id' => \Yii::$app->user->id
        ])->one();
    }

    public function actionView($id)
    {
        return $this->findAndCheck($id);
    }

    public function actionUpdate($id){
        $payoutMethod = $this->findAndCheck($id);
        $payoutMethod->load(\Yii::$app->request->getBodyParams());
        $payoutMethod->save();
        return $payoutMethod;
    }

    public function actionDelete($id){
        $payoutMethod = $this->findAndCheck($id);
        return $payoutMethod->delete();
    }

    private function findAndCheck($id){
        $payout = PayoutMethod::findOne($id);
        /**
         * @var PayoutMethod $payout
         */
        if($payout == null){
            throw new NotFoundHttpException("PayoutMethod not found");
        }
        if($payout->userHasAccess(\Yii::$app->user)){
            return $payout;
        }
        throw new ForbiddenHttpException("Access denied to payoutmethod");
    }
}