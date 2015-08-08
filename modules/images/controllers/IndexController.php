<?php
namespace app\modules\images\controllers;

use app\modules\images\components\ImageManager;
use Yii;
use yii\helpers\Html;


/**
 * Site controller
 */
class IndexController extends \app\controllers\Controller
{
    public function actionIndex($id, $w = null, $h = null, $q = null, $fit = null, $folder1 = null, $folder2 = null, $folder3 = null){
        $server = (new ImageManager())->getServer();

        if(isset($folder1) && $folder1 == 'kidup'){
            $folder1 = null;
            $server->setSourcePathPrefix('/modules/images/images/');
        }else{
            $server->setSourcePathPrefix('/runtime/user-uploads/'.ImageManager::createSubFolders($id));
        }

        // settings for image
        $options = [];
        if($w !== null) $options['w'] = $w;
        if($h !== null) $options['h'] = $h;
        if($q !== null){
            $options['fm'] = 'pjpg';
            $options['q'] = $q;
        }
        if($fit !== null){
            $options['fit'] = $fit;
        }
        // filename
        $filename = $id;
        $folders = [];
        if($folder1 !== null) $folders[] = $folder1;
        if($folder2 !== null) $folders[] = $folder2;
        if($folder3 !== null) $folders[] = $folder3;
        if(count($folders) > 0) $folders[] = '';
        $filename = implode('/', $folders).$filename;
        $server->outputImage($filename, $options);
        exit();
    }
}
