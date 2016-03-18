<?php
namespace api\controllers;

use images\components\ImageHelper;
use api\models\KodeUp;
use item\models\itemHasMedia\ItemHasMedia;
use user\models\Profile;

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
            'guest' => ['recommendations', 'rate'],
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
    public function actionRecommendations($device_id, $chick_mode = false, $batch = 7)
    {
        $c = KodeUp::find()->where(['device_id' => $device_id])->count();
        if ($c == 0) {
            $res = [];
            if ($chick_mode) {
                $profiles = Profile::find()->where('img != "kidup/user/default-face.jpg"')->orderBy("rand()")->limit($batch)->all();
                foreach ($profiles as $profile){
                    $res[] = ImageHelper::url($profile->getAttribute('img'));
                }
            }else{
                $items = ItemHasMedia::find()->orderBy("rand()")->limit($batch)->all();
                foreach ($items as $itemHM){
                    $res[] = ImageHelper::url($itemHM->media->getAttribute('file_name'));
                }
            }
            return $res;
        }
        return false;
    }
}