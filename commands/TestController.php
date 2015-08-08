<?php

namespace app\commands;

use app\backup\Database;
use app\backup\File;
use app\components\Event;
use app\modules\booking\models\Payin;
use yii\console\Controller;
use Yii;
use app\modules\images\components\ImageManager;

class TestController extends Controller
{
    public function actionToS3()
    {
        $dir = scandir('/vagrant/users/org');
        foreach ($dir as $d) {
            if (strpos($d, ".png") == false && strpos($d, ".jpg") == false) {
                continue;
            }
            $o = $d;
            $d = str_replace("_original", '', $d);
            $d = explode("_", $d)[2];
            $filename = $d;
            $dir = '';
            $dir .= 'user-uploads/' . ImageManager::createSubFolders($filename);
            $i = new ImageManager();
            $i->filesystem->createDir($dir);
            $i->filesystem->write($dir . '/' . $d, file_get_contents('/vagrant/users/org/'.$o));
        }

    }
}
