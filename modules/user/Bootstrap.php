<?php

namespace app\modules\user;

use yii\authclient\Collection;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;
use yii\web\GroupUrlRule;

/**
 * Bootstrap class registers module and user application component. It also creates some url rules which will be applied
 * when UrlManager.enablePrettyUrl is enabled.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Bootstrap implements BootstrapInterface
{
    /** @var array Model's map */
    private $_modelMap = [
        'User' => 'app\modules\user\models\User',
        'Account' => 'app\modules\user\models\Account',
        'Profile' => 'app\modules\user\models\Profile',
        'RegistrationForm' => 'app\modules\user\models\RegistrationForm',
        'ResendForm' => 'app\modules\user\models\ResendForm',
        'LoginForm' => 'app\modules\user\models\LoginForm',
        'SettingsForm' => 'app\modules\user\models\SettingsForm',
        'RecoveryForm' => 'app\modules\user\models\RecoveryForm',
        'UserSearch' => 'app\modules\user\models\UserSearch',
    ];

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
            foreach ($this->_modelMap as $name => $definition) {
                $class = "app\\modules\\user\\models\\" . $name;
                \Yii::$container->set($class, $definition);
                $modelName = is_array($definition) ? $definition['class'] : $definition;
                $module->modelMap[$name] = $modelName;
                if (in_array($name, ['User', 'Profile', 'Account'])) {
                    \Yii::$container->set($name . 'Query', function () use ($modelName) {
                        return $modelName::find();
                    });
                }
            }
            \Yii::$container->setSingleton(Finder::className(), [
                'userQuery' => \Yii::$container->get('UserQuery'),
                'accountQuery' => \Yii::$container->get('AccountQuery'),
            ]);

            if ($app instanceof ConsoleApplication) {
                $module->controllerNamespace = 'app\modules\user\commands';
            } else {
                \Yii::$container->set('yii\web\User', [
                    'enableAutoLogin' => true,
                    'loginUrl' => ['/user/security/login'],
                    'identityClass' => $module->modelMap['User'],
                ]);

                $configUrlRule = [
                    'prefix' => $module->urlPrefix,
                    'rules' => $module->urlRules
                ];

                if ($module->urlPrefix != 'user') {
                    $configUrlRule['routePrefix'] = 'user';
                }

                $app->get('urlManager')->rules[] = new GroupUrlRule($configUrlRule);

                if (!$app->has('authClientCollection')) {
                    $app->set('authClientCollection', [
                        'class' => Collection::className(),
                    ]);
                }
            }

            $app->get('i18n')->translations['user*'] = [
                'class' => PhpMessageSource::className(),
                'basePath' => '@app/messages',
            ];
            $app->get('i18n')->translations['title*'] = [
                'class' => PhpMessageSource::className(),
                'basePath' => '@app/messages',
            ];

            $defaults = [
                'welcomeSubject' => \Yii::t('user', 'Welcome to {0}', \Yii::$app->name),
                'confirmationSubject' => \Yii::t('user', 'Confirm account on {0}', \Yii::$app->name),
                'reconfirmationSubject' => \Yii::t('user', 'Confirm email change on {0}', \Yii::$app->name),
                'recoverySubject' => \Yii::t('user', 'Complete password reset on {0}', \Yii::$app->name)
            ];

            \Yii::$container->set('app\modules\user\Mailer', array_merge($defaults, $module->mailer));
        }

    }
}