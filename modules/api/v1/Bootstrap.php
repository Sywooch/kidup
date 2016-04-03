<?php

namespace api\v1;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

class Bootstrap implements BootstrapInterface
{
    /** @inheritdoc */
    public function bootstrap($app)
    {

        /**
         * @var \api\Module $apiModule
         */
        $apiModule = $app->getModule('api');
        $module = $apiModule->getModule('v1');
        $apiModule->registerUrls($module->urlRules, 'v1');
    }

}