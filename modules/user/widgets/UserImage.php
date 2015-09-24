<?php
namespace app\modules\user\widgets;

use app\modules\images\components\ImageHelper;
use app\modules\user\models\Profile;
use kartik\widgets\Widget;

class UserImage extends Widget
{
    public $user_id;
    public $width = '50px';
    private $profile;

    public function init($data = null)
    {
    }

    public function run()
    {
        $this->profile = Profile::find()->where(['user_id' => $this->user_id])->one();

        if ($this->profile !== null) {
            $img = $this->profile->getAttribute('img');

            return ImageHelper::img($img, [
                'q' => 90,
                'w' => str_replace('px', '', $this->width),
                'h' => str_replace('px', '', $this->width),
                'fit' => 'crop'
            ], ['class' => "avatar img-circle"]);
        }
    }
}