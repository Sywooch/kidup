<?php
namespace message\assets;

use yii\web\AssetBundle;

class MessageAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/message/views/chat/assets';

    public $css = [
        'chat.less',
    ];
}