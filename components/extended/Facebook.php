<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components\extended;

use yii\authclient\OAuth2;
use yii\helpers\ArrayHelper;

/**
 * Facebook allows authentication via Facebook OAuth.
 *
 * In order to use Facebook OAuth you must register your application at <https://developers.facebook.com/apps>.
 *
 * Example application configuration:
 *
 * ~~~
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'facebook' => [
 *                 'class' => 'yii\authclient\clients\Facebook',
 *                 'clientId' => 'facebook_client_id',
 *                 'clientSecret' => 'facebook_client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ~~~
 *
 * @see https://developers.facebook.com/apps
 * @see http://developers.facebook.com/docs/reference/api
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class Facebook extends OAuth2
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://www.facebook.com/dialog/oauth';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://graph.facebook.com/oauth/access_token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://graph.facebook.com';
    /**
     * @inheritdoc
     */
    public $scope = 'email';

    /**
    * @var array list of attribute names, which should be requested from API to initialize user attributes.
    * @since 2.0.5
    */
   public $attributeNames = [
       'name',
       'email',
   ];

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        $basicData = $this->api('me', 'GET', [
            'fields' => implode(',', $this->attributeNames),
        ]);
        $profileImg = ['profile_img_url'  => $this->requestImage($basicData["id"].'/picture?width=320&height=320', 'GET', [])];
        return ArrayHelper::merge($basicData, $profileImg);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'facebook';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Facebook';
    }

    public function requestImage($url, $method, $params){
        $accessToken = $this->getAccessToken();
        $params['access_token'] = $accessToken->getToken();
        $url = $this->apiBaseUrl . '/' .$url;
        $curlOptions = $this->mergeCurlOptions(
            $this->defaultCurlOptions(),
            $this->getCurlOptions(),
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $url,
            ],
            $this->composeRequestCurlOptions(strtoupper($method), $url, $params)
        );
        $curlResource = curl_init();
        foreach ($curlOptions as $option => $value) {
            curl_setopt($curlResource, $option, $value);
        }
        $response = curl_exec($curlResource);
        $responseHeaders = curl_getinfo($curlResource);

        // check cURL error
        $errorNumber = curl_errno($curlResource);
        $errorMessage = curl_error($curlResource);

        curl_close($curlResource);
        
        return $responseHeaders['redirect_url'];
    }
}
