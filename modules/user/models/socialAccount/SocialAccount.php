<?php

namespace user\models\socialAccount;

use images\components\ImageManager;
use user\models\user\User;
use Yii;

class SocialAccount extends SocialAccountBase
{

    /**
     * @return bool Whether this social account is connected to user.
     */
    public function getIsConnected()
    {
        return $this->user_id != null;
    }

    /**
     * @return mixed Json decoded properties.
     */
    public function getDecodedData()
    {
        if ($this->_data == null) {
            $this->_data = json_decode($this->data);
        }
        return $this->_data;
    }

    /**
     * Uses data from the social account provider to save some user properties
     * @param User $user
     * @return bool
     */
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
                if (!$user->profile->save()) {
                    Yii::error("User profile update from social accout failed");
                    Yii::error($user->profile->getErrors());
                }
            }
        }
        return true;
    }

}