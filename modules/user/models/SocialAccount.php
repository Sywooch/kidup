<?php
namespace user\models;

use images\components\ImageManager;

/**
 *
 * @property \user\Module $module
 *
 */
class SocialAccount extends base\SocialAccount
{
    public function fillUserDetails(User $user)
    {
        $data = \yii\helpers\Json::decode($this->data);
        if (isset($data['name'])) {
            $user->profile->first_name = explode(' ', $data['name'])[0];
            $user->profile->last_name = str_replace($user->profile->first_name . ' ', '', $data['name']);
            $user->profile->save();
        }

        // facebook profile pic
        if (isset($data['profile_img_url'])) {
            $url = $data['profile_img_url'];
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
                $user->profile->setScenario('social-connect-image');
                $user->profile->setAttribute('img', $uploadedFile);
                if(!$user->profile->save()){
                    \yii\helpers\VarDumper::dump($user->profile->getErrors(),10,true); exit();
                }
            }
        }
        return true;
    }
}