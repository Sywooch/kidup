<?php
namespace api\controllers;

use api\models\oauth\OauthAccessToken;
use api\models\oauth\OauthClient;
use api\models\oauth\OauthRefreshToken;
use api\models\User;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;

class UserController extends Controller
{
    public function init(){
        $this->modelClass = User::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['index', 'view', 'create', 'x'],
            'user' => ['update']
        ];
    }

    public function actionX(){
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

    public function actions(){
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }
}