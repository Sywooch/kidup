<?php

namespace app\modules\home\controllers;

use app\extended\web\Controller;
use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\HttpException;

class ErrorController extends Controller
{
    public function actionError()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            return 'Exception not triggered';
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = $this->defaultName ?: Yii::t('yii', 'Error');
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            if (YII_DEBUG) {
                echo "Unkown Error: " . $exception->getMessage();
                debug_backtrace();
                exit();
            }
            $message = $this->defaultMessage ?: Yii::t('yii', 'An internal server error occurred.');
            if (YII_DEBUG) {
                get_call_stack();
                exit();
            }
        }

        if (Yii::$app->getRequest()->getIsAjax()) {
            return "$name: $message";
        } else {
            return $this->render('error', [
                'name' => $name,
                'message' => $message,
                'exception' => $exception,
            ]);
        }
    }
}
