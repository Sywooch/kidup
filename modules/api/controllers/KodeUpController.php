<?php
namespace api\controllers;

use api\models\Media;
use images\components\ImageHelper;
use api\models\KodeUp;
use item\models\itemHasMedia\ItemHasMedia;
use user\models\profile\Profile;

class KodeUpController extends Controller
{


    public function init()
    {
        $this->modelClass = KodeUp::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['recommendations', 'create'],
            'user' => []
        ];
    }


    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['update']);
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }

    /**
     * Registration of a user
     * @param string $email
     * @param string $password
     * return array|User
     */
    public function actionRecommendations($device_id, $chick_mode = false, $batch = 10)
    {

        $c = KodeUp::find()->where(['device_id' => $device_id])->count();
        if ($c == 0) {
            $res = [];
            if ($chick_mode) {
                $profiles = Profile::find()->where('img != "kidup/user/default-face.jpg"')
                    ->orderBy("rand()")
                    ->limit($batch)->all();
                foreach ($profiles as $profile) {
                    $res[] = ImageHelper::url($profile->getAttribute('img'));
                }
            } else {
                $items = ItemHasMedia::find()->orderBy("rand()")->limit($batch)->all();
                foreach ($items as $itemHM) {
                    $res[] = ImageHelper::url($itemHM->media->getAttribute('file_name'));
                }
            }
            return $res;
        } else {
            $k = new KodeUp();
            $set = $k->getRecommendations($device_id);
            $set = $k->filterHadBefore($device_id, $set);
            if ($chick_mode != false) {
                $set = $k->filterMedia($set);
            } else {
                $set = $k->filterChicks($set);
            }
            $random = round(0.2 * $batch);
            $recSize = $batch - $random;
            $set = array_slice($set, 0, $recSize);
            for ($i = 0; $i < $random; $i++) {
                if ($chick_mode != false) {
                    $profiles = Profile::find()->where('img != "kidup/user/default-face.jpg"')->orderBy("rand()")->one();
                    $set[] = $profiles->getAttribute('img');
                } else {
                    $item = Media::find()->orderBy("rand()")->one();
                    $set[] = $item->getAttribute('file_name');
                }
            }
            shuffle($set);
            foreach ($set as &$item) {
                $item = ImageHelper::url($item);
                if (strpos($item, ".jpg") === false && strpos($item, ".png") === false) {
                    $item = rtrim($item, '?') . ".jpg?";
                }
            }
            return $set;
        }
    }
}