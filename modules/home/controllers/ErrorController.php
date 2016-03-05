<?php

namespace home\controllers;

use app\extended\web\Controller;
use Yii;
use app\extended\base\Exception;
use yii\base\UserException;
use yii\web\HttpException;
use yii\web\Response;

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
            $name = $this->defaultName ?: Yii::t('kidup.error', 'Error');
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
            $message = $this->defaultMessage ?: Yii::t('kidup.internal_server_error',
                'An internal server error occurred.');
        }
        
        $route = @\Yii::$app->request->getUrl();
        if(strpos($route, '/api') === 0){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'name' => $name,
                'message' => $message,
                'exception' => $exception,
            ];
        }

        return $this->render('error', [
            'name' => $name,
            'message' => $message,
            'exception' => $exception,
        ]);
    }
}
