<?php

namespace api\controllers;

use api\models\oauth\OauthAccessToken;
use api\models\oauth\OauthClient;
use api\models\oauth\OauthRefreshToken;
use user\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;

class Oauth2Controller extends Controller
{
    public function init(){
        $this->modelClass = User::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['token', 'refresh'],
            'user' => ['']
        ];
    }

    public function actions(){
        // no default controller actions
        return [];
    }

    public function actionToken()
    {

        $params = \Yii::$app->request->post();
        /**
         * @var OauthClient $client
         */
        $client = OauthClient::find()->where([
            'client_id' => $params['client_id'],
            'client_secret' => $params['client_secret'],
        ])->one();
        if($client == null){
            throw new BadRequestHttpException("Client not found.");
        }
        /**
         * @var User $user
         */
        $user = User::findOne(['email' => $params['username']]);
        if($user == null){
            throw new BadRequestHttpException("User not found.");
        }
        if(!\Yii::$app->security->validatePassword($params['password'], $user->password_hash)){
            throw new UnauthorizedHttpException("Login credentials not correct.");
        }
        $token = OauthAccessToken::make($user, $client);
        $refreshToken = OauthRefreshToken::make($user, $client);
        

        return [
            'token' => $token->access_token,
            'refresh_token' => $refreshToken->refresh_token,
            'expires_in' => $token->expires - time()
        ];
    }

    public function actionRefresh()
    {
        if(\Yii::$app->request->isOptions){
            return true;
        }

        $params = \Yii::$app->request->post();

        /**
         * @var OauthRefreshToken $oldRefreshToken
         */
        $oldRefreshToken = OauthRefreshToken::findOne(['refresh_token' => $params['refresh_token']]);
        if($oldRefreshToken == null){
            throw new BadRequestHttpException("Refresh token not found.");
        }

        $token = OauthAccessToken::make($oldRefreshToken->user, $oldRefreshToken->client);
        $refreshToken = OauthRefreshToken::make($oldRefreshToken->user, $oldRefreshToken->client);
        $oldRefreshToken->delete();

        OauthAccessToken::deleteAll(['user_id' => $token->user_id, 'client' => $token->client_id, ['<', 'expires', time()]]);
        OauthRefreshToken::deleteAll(['user_id' => $token->user_id, 'client' => $token->client_id, ['<', 'expires', time()]]);

        return [
            'token' => $token->access_token,
            'refresh_token' => $refreshToken->refresh_token,
            'expires_in' => $token->expires - time()
        ];
    }
}
