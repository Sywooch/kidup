<?php
namespace app\modules\user\models;

use app\modules\images\components\ImageManager;

/**
 *
 * @property \app\modules\user\Module $module
 *
 */
class SocialAccount extends \app\models\base\SocialAccount
{
    public function fillUserDetails(User $user)
    {
        $data = \yii\helpers\Json::decode($this->data);
        if (isset($data['name'])) {
            $user->profile->first_name = explode(' ', $data['name'])[0];
            $user->profile->last_name = str_replace($user->profile->first_name . ' ', '', $data['name']);
            $user->profile->save();
        }
        // twitter profile pic
        if (isset($data['profile_image_url'])) {
            $fileData = file_get_contents($data['profile_image_url']);
            $uploadedFile = (new ImageManager())->store($fileData, $data['profile_image_url']);
            if ($uploadedFile !== false) {
                $user->profile->setAttribute('img', $uploadedFile);
                $user->profile->save();
            }
        }
        // facebook profile pic
        if (isset($data['picture'])) {
            $url = $data['picture']['data']['url'];
            $fileData = file_get_contents($url);
            // find if jpg or png
            if (strpos($url, '.png') !== false) {
                $uploadedFile = (new ImageManager())->store($fileData, 't.png');
            } elseif (strpos($url, '.jpg') !== false) {
                $uploadedFile = (new ImageManager())->store($fileData, 't.jpg');
            } else {
                $uploadedFile = false;
            }
            if ($uploadedFile !== false) {
                $user->profile->setAttribute('img', $uploadedFile);
                $user->profile->save();

            }
        }
        return true;
    }
}