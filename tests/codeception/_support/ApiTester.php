<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * Define custom actions here
     */
    public function sendGETAsUser($user, $request, $params = [])
    {
        $params['access-token'] = \codecept\_support\UserHelper::getUserAccessToken($user);
        return $this->sendGET($request, $params);
    }

    public function sendPOSTAsUser($user, $url, $params = [])
    {
        $url .= "?access-token=" . \codecept\_support\UserHelper::getUserAccessToken($user);
        return $this->sendPOST($url, $params);
    }
}
