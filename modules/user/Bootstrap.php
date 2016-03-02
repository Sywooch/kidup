<?php

namespace user;

use yii\authclient\Collection;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
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
        'User' => '\user\models\User',
        'Account' => '\user\models\Account',
        'Profile' => '\user\models\Profile',
        'RegistrationForm' => '\user\models\RegistrationForm',
        'ResendForm' => '\user\models\ResendForm',
        'LoginForm' => '\user\models\LoginForm',
        'SettingsForm' => '\user\models\SettingsForm',
        'RecoveryForm' => '\user\models\RecoveryForm',
        'UserSearch' => '\user\models\UserSearch',
    ];

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
            foreach ($this->_modelMap as $name => $definition) {
                $class = "app\\modules\user\\models\\" . $name;
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
                $module->controllerNamespace = '\user\commands';
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


            $defaults = [
            ];

            \Yii::$container->set('\user\Mailer', array_merge($defaults, $module->mailer));
        }

    }
}