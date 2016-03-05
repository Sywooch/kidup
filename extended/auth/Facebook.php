<?php
namespace app\extended\auth;

use yii\helpers\ArrayHelper;


class Facebook extends \yii\authclient\clients\Facebook
{
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

    protected function initUserAttributes()
    {
        $basicData = $this->api('me', 'GET', [
            'fields' => implode(',', $this->attributeNames),
        ]);
        $profileImg = ['profile_img_url'  => $this->requestImage($basicData["id"].'/picture?width=320&height=320', 'GET', [])];
        return ArrayHelper::merge($basicData, $profileImg);
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
