<?php
namespace app\components;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Yii;
use yii\base\Component;
use yii\helpers\Json;

class Log extends Component
{
    private $logger;
    public function __construct(){
//        $this->logger = new Logger('logger');
//        $this->logger->pushHandler(new StreamHandler(\Yii::$aliases['@runtime'].'/logs/all.log'));
//        $this->logger->pushHandler(new StreamHandler(\Yii::$aliases['@runtime'].'/logs/info.log', Logger::INFO));
//        $this->logger->pushHandler(new StreamHandler(\Yii::$aliases['@runtime'].'/logs/notice.log', Logger::NOTICE));
//        $this->logger->pushHandler(new StreamHandler(\Yii::$aliases['@runtime'].'/logs/debug.log', Logger::DEBUG));
//        $this->logger->pushHandler(new StreamHandler(\Yii::$aliases['@runtime'].'/logs/error.log', Logger::ERROR));
//        $this->logger->pushHandler(new StreamHandler(\Yii::$aliases['@runtime'].'/logs/critical.log', Logger::CRITICAL));
//        $this->logger->pushHandler(new StreamHandler(\Yii::$aliases['@runtime'].'/logs/emergency.log', Logger::EMERGENCY));
        return parent::__construct();
    }

    public function debug($message, $data = []){
        $data = $this->addUserData($data);
        $data = $this->addBackTraceData($data);
//        $this->logger->addDebug($message, $data);
    }

    public function info($message, $data = null){
        $data = $this->addUserData($data);
//        $this->logger->addInfo($message, $data);
    }

    public function notice($message, $data = []){
        $data = $this->addUserData($data);
        $data = $this->addBackTraceData($data);
//        $this->logger->addNotice($message, $data);
    }

    public function warning($message, $data = []){
        $data = $this->addBackTraceData($data);
//        $this->logger->addWarning($message, $data);
    }

    public function error($message, $data = []){
        $data = $this->addBackTraceData($data);
//        $this->logger->addError($message, $data);
    }

    public function critical($message, $data = []){
        $data = $this->addBackTraceData($data);
//        $this->logger->addCritical($message, $data);
    }

    public function emergengy($message, $data = []){
        $data = $this->addBackTraceData($data);
//        $this->logger->addEmergency($message, $data);
    }

    private function addUserData($data){
        if(is_string($data)){
            $data = [
                'data' => $data,
            ];
        }
        if(YII_CONSOLE){
            $data['_console'] = true;
        }else{
            if(!\Yii::$app->user->isGuest){
                $data['_user_id'] = \Yii::$app->user->id;
            }
            $data['_session_id'] = \Yii::$app->session->getId();
        }
        return $data;
    }

    private function addBackTraceData($data){
        if(count($data) > 0){
            if(is_string($data)){
                $data = [
                    'data' => $data
                ];
            }else{
                $data = Json::decode(Json::encode($data));
            }
        }
        $data['_backtrace'][] = debug_backtrace()[1];
        return $data;
    }
}