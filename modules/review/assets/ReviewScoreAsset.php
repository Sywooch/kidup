<?php
namespace review\assets;

use yii\web\AssetBundle;

class ReviewScoreAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/review/widgets/assets';

    public $css = [
        'score.less',
    ];
}