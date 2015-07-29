<?php
namespace app\controllers;

use Yii;


/**
 * Site controller
 */
class FileController extends \app\controllers\Controller
{
    public function actionIndex($id){
        $file = \app\modules\item\components\MediaManager::get($id);
        if(strpos(strtolower($id), ".jpg") > -1)
            header('Content-Type: image/jpeg');
        if(strpos(strtolower($id), ".png") > -1)
            header('Content-Type: image/png');
        if(strpos(strtolower($id), ".gif") > -1)
            header('Content-Type: image/gif');
        echo $file;
        exit();
    }
}
