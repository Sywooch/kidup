<?php

namespace app\modules\message;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\message\controllers';

    public function init()
    {
        $this->registerTranslations();
        parent::init();
    }

    public function registerTranslations()
    {
        \Yii::$app->i18n->translations['modules/message/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/users/messages',
            'fileMap' => [
                'modules/message' => 'message.php',
//                'modules/message/form' => 'form.php',
            ],
        ];
    }

    public static function t($message, $default, $params = [], $language = null)
    {
        $viewFile = 'x';
        return \Yii::t('modules/users/' . $viewFile, $message, $params, $language);
    }
}
