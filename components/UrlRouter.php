<?php
namespace app\components;

use yii\web\UrlRuleInterface;
use yii\base\Object;

class UrlRouter extends Object implements UrlRuleInterface
{

    private $urls = [
        'home' => 'home/home',
        'search' => 'home/home',
        'item/view' => 'item/view/index',
        'item/search' => 'item/search/index',
        'login' => 'user/login',
        'logout' => 'user/logout',
        'booking/confirm' => 'item/booking/confirm',
        'message/inbox' => 'message/inbox',
        'message/conversation' => 'message/conversation',
        'booking/view' => 'booking/view'
    ];

    public function createUrl($manager, $route, $params){
        return false;
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        $params = $request->queryParams;

        if(isset($this->urls[$pathInfo])){
            return [$this->urls[$pathInfo], $params];
        }

        return [$pathInfo, $params];  // this rule does not apply
    }
}