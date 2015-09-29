<?php
namespace booking\assets;

use yii\web\AssetBundle;

class BookingViewsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/booking/views/assets';

    public $css = [
        'bookingViews.less',
    ];

    public $depents = [
        'app\assets\AppAsset'
    ];
}