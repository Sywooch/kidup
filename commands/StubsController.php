<?php
namespace app\commands;
use yii\console\Controller;
use yii\console\Exception;

class StubsController extends Controller
{
    public $outputFile = null;
    protected function getTemplate()
    {
        return <<<TPL
<?php
/**
 * Yii app stub file. Autogenerated by yii2-stubs-generator (stubs console command).
 * Used for enhanced IDE code autocompletion.
 * Updated on {time}
 */
class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication the application instance
     */
    public static \$app;
}
/**{stubs}
 **/
abstract class BaseApplication extends yii\base\Application
{
}
/**{stubs}
 **/
class WebApplication extends yii\web\Application
{
}
/**{stubs}
 **/
class ConsoleApplication extends yii\console\Application
{
}
TPL;
    }
    public function actionIndex($app)
    {
        $path = $this->outputFile ? $this->outputFile : \Yii::$app->getVendorPath() . DIRECTORY_SEPARATOR . 'Yii.php';
        $components = [];
        foreach (\Yii::$app->requestedParams as $app) {
            $configFile = $app . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
            if (!file_exists($configFile)) {
                throw new Exception('Config file doesn\'t exists: ' . $configFile);
            }
            $config = include($configFile);
            foreach ($config['components'] as $name => $component) {
                if (!isset($component['class'])) {
                    continue;
                }
                $components[$name][] = $component['class'];
            }
        }
        $stubs = '';
        foreach ($components as $name => $classes) {
            if(!isset($classes['class'])) continue;

            $classes =  $classes['class'];
            $stubs .= "\n * @property {$classes} \$$name";
        }
        $content = str_replace('{stubs}', $stubs, $this->getTemplate());
        $content = str_replace('{time}', date(DATE_ISO8601), $content);
        if($content!=@file_get_contents($path)) {
            file_put_contents($path, $content);
        }
    }
}