<?php
namespace app\modules\images\widgets;

use app\modules\images\components\ImageHelper;
use app\modules\user\models\Profile;
use kartik\widgets\Widget;

class Image extends Widget
{
    public $name;
    public $options;
    public $imageOptions;

    public function run()
    {
        return ImageHelper::img($this->name, $this->imageOptions, $this->options);
    }
}