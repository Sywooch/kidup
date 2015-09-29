<?php
namespace images\widgets;

use \images\components\ImageHelper;
use \user\models\Profile;
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