<?php
namespace app\modules\images\controllers;

use app\modules\images\components\ImageManager;
use Yii;
use yii\base\DynamicModel;
use yii\web\BadRequestHttpException;
use yii\widgets\ActiveForm;


/**
 * Site controller
 */
class IndexController extends \app\controllers\Controller
{
    public function actionIndex(
        $id,
        $w = null,
        $h = null,
        $q = null,
        $fit = null,
        $folder1 = null,
        $folder2 = null,
        $folder3 = null,
        $fm = null
    ) {

        $model = DynamicModel::validateData(compact('id', 'w', 'h', 'q', 'fit', 'folder1', 'folder2', 'folder3', 'fm', 'bri'),
            [
                [['id', 'fit', 'folder1', 'folder2', 'folder3', 'fm'], 'string', 'max' => 52],
                [['w', 'h'], 'integer', 'min' => 1, 'max' => 5000],
                [['q'], 'integer', 'min' => 1, 'max' => 100],
                ['fm', 'in', 'range' => ['jpg', 'pjpg', 'png']],
                ['fit', 'in', 'range' => ['contain', 'max', 'fill', 'stretch', 'crop']]
            ]);
        if ($model->hasErrors()) {
            throw new BadRequestHttpException((new ActiveForm())->errorSummary($model));
        }
        $server = (new ImageManager())->getServer();

        if (isset($folder1) && $folder1 == 'kidup') {
            $folder1 = null;
            $server->setSourcePathPrefix('/modules/images/images/');
        } else {
            $server->setSourcePathPrefix('/runtime/user-uploads/' . ImageManager::createSubFolders($id));
        }

        // settings for image
        $options = [];
        if ($w !== null) {
            $options['w'] = $w;
        }
        if ($h !== null) {
            $options['h'] = $h;
        }
        if ($fm !== null) {
            $options['fm'] = $fm;
        }

        if ($q !== null) {
            if ($fm === null) {
                $options['fm'] = 'pjpg';
            }
            $options['q'] = $q;
        }
        if ($fit !== null) {
            $options['fit'] = $fit;
        }
        // filename
        $filename = $id;
        $folders = [];
        if ($folder1 !== null) {
            $folders[] = $folder1;
        }
        if ($folder2 !== null) {
            $folders[] = $folder2;
        }
        if ($folder3 !== null) {
            $folders[] = $folder3;
        }
        if (count($folders) > 0) {
            $folders[] = '';
        }
        $filename = implode('/', $folders) . $filename;
        $server->outputImage($filename, $options);
        exit();
    }
}
