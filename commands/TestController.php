<?php

namespace app\commands;

use api\models\Media;
use images\components\ImageHelper;
use user\models\Profile;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionKodeUp()
    {
        $res = [];
        $profiles = Profile::find()->where('img != "kidup/user/default-face.jpg"')->orderBy("rand()")->all();
        foreach ($profiles as $profile) {
            $res[] = ImageHelper::url($profile->getAttribute('img'), [
                'crop' => 'faces',
                'fit' => 'max',
                'w' => 250,
                'h' => 250
            ]);
        }
        $items = Media::find()->all();
        foreach ($items as $itemHM) {
            $res[] = ImageHelper::url($itemHM->getAttribute('file_name'));
        }
        file_put_contents("urls.json", json_encode($res));
    }
}
