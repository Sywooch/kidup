<?php
namespace app\modules\user\widgets;

use app\interfaces\RequestableWidgetInterface;
use app\modules\item\components\MediaManager;
use app\modules\user\models\Profile;
use kartik\widgets\Widget;

class UserImage extends Widget
{
    public $user_id;
    public $width = '50px';
    private $profile;

    public function init($data = null){
    }

    public function run()
    {
        $this->profile = Profile::find()->where(['user_id' => $this->user_id])->one();

        $img = $this->profile->getAttribute('img');

        $url = MediaManager::getUrl($img, MediaManager::THUMB);

        return $this->render('user_image', [
            'url' => $url,
            'profile' => $this->profile,
            'width' => $this->width
        ]);
    }
}