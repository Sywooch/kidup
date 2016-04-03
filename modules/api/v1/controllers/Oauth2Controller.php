<?php

namespace api\v1\controllers;

use api\v1\models\oauth\OauthAccessToken;
use api\v1\models\oauth\OauthClient;
use api\v1\models\oauth\OauthRefreshToken;
use app\extended\auth\Facebook;
use app\helpers\Event;
use user\models\Account;
use user\models\socialAccount\SocialAccount;
use user\models\user\User;
use Yii;
use yii\authclient\OAuthToken;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class Oauth2Controller extends Controller
{
    public function init()
    {
        $this->modelClass = User::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['token', 'refresh', 'facebook-login'],
            'user' => ['']
        ];
    }

    public function actions()
    {
        // no default controller actions
        return [];
    }

    public function actionToken()
    {

        $params = \Yii::$app->request->post();

        /**
         * @var User $user
         */
        $user = User::findOne(['email' => $params['username']]);
        if ($user == null) {
            throw new BadRequestHttpException("User not found.");
        }
        if (!\Yii::$app->security->validatePassword($params['password'], $user->password_hash)) {
            throw new UnauthorizedHttpException("Login credentials not correct.");
        }
        return $this->oauthParams($user, $params['client_id'], $params['client_secret']);

    }

    public function actionRefresh()
    {
        $params = \Yii::$app->request->post('refresh_token');

        /**
         * @var OauthRefreshToken $oldRefreshToken
         */
        $oldRefreshToken = OauthRefreshToken::findOne(['refresh_token' => $params]);
        if ($oldRefreshToken == null) {
            throw new NotFoundHttpException("Refresh token not found.");
        }

        $token = OauthAccessToken::make($oldRefreshToken->user, $oldRefreshToken->client);
        $refreshToken = OauthRefreshToken::make($oldRefreshToken->user, $oldRefreshToken->client);
        $oldRefreshToken->delete();

        OauthAccessToken::deleteAll([
            'user_id' => $token->user_id,
            'client' => $token->client_id,
            ['<', 'expires', time()]
        ]);
        OauthRefreshToken::deleteAll([
            'user_id' => $token->user_id,
            'client' => $token->client_id,
            ['<', 'expires', time()]
        ]);

        return [
            'token' => $token->access_token,
            'refresh_token' => $refreshToken->refresh_token,
            'expires_in' => $token->expires - time()
        ];
    }

    /**
     * @api {post} oauth2/facebook-login
     * @apiName         facebookLogin
     * @apiGroup        Oauth2
     * @apiDescription  Seriously ugly programmed request to do facebook login.
     *
     * @apiParam {Number} id facebook id
     * @apiParam {Number} client_id client id of uaoth2
     * @apiParam {String} client_secret client secret of uaoth2
     * @apiParam {String} data Json with oauth2 data
     * @apiParam {String} email User email
     * @apiParam {String} first_name User's first name
     * @apiParam {String} last_name User's last name
     * @apiParam {String} access_token Fb access token
     *
     * @return array
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */

    public function actionFacebookLogin()
    {
        if (!\Yii::$app->request->isPost) {
            throw new BadRequestHttpException("Should be a POST");
        }
        $params = \Yii::$app->request->getBodyParams();
        if (!isset($params['id'])) {
            throw new BadRequestHttpException("Id should be set in post");
        }
        if (!isset($params['data'])) {
            throw new BadRequestHttpException("Data should be set in post");
        }
        $account = SocialAccount::find()->where(['provider' => 'facebook', 'client_id' => $params['id']])->one();

        if ($account === null) {
            if ($params['data'] == "[object Object]") {
                $data = [];
            } else {
                $data = $params['data'];
            }

            $account = \Yii::createObject([
                'class' => SocialAccount::className(),
                'provider' => 'facebook',
                'client_id' => $params['id'],
                'data' => json_encode($data),
            ]);


            // see if user already exists
            $user = User::findOne(['email' => $params['email']]);
            if ($user !== null) {
                $account->user_id = $user->id;
                $account->save(false);
            } else {
                /** @var User $user */
                $user = \Yii::createObject([
                    'class' => User::className(),
                    'scenario' => 'connect'
                ]);
                $user->email = $params['email'];
                $user->create();
                $account->user_id = $user->id;
                $account->save(false);

                $fb = new Facebook();
                $oauthToken = new OAuthToken();
                $oauthToken->setToken($params['access_token']);
                $fb->setAccessToken($oauthToken);
                $basicData = $fb->api('me', 'GET', [
                    'fields' => implode(',', ['name', 'email']),
                ]);

                $data = json_decode($account->data, true);
                $account->data = json_encode(array_merge($data, [
                    'profile_img_url' =>
                        $fb->requestImage($basicData["id"] . '/picture?width=320&height=320', 'GET', [])
                ]));

                $account->save(false);

                /**
                 * @var SocialAccount $socialAccount
                 */
                $account->fillUserDetails($user);
                $account->user->profile->first_name = $params['first_name'];
                $account->user->profile->last_name = $params['last_name'];
                $account->user->profile->save(false);

                Event::trigger($user, User::EVENT_USER_REGISTER_DONE);
            }
        }

        return $this->oauthParams($account->user, $params['client_id'], $params['client_secret']);
    }

    private function oauthParams(User $user, $clientId, $clientSecret)
    {
        /**
         * @var OauthClient $client
         */
        $client = OauthClient::find()->where([
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ])->one();
        if ($client == null) {
            throw new BadRequestHttpException("Client not found.");
        }

        $token = OauthAccessToken::make($user, $client);
        $refreshToken = OauthRefreshToken::make($user, $client);

        return [
            'token' => $token->access_token,
            'refresh_token' => $refreshToken->refresh_token,
            'expires_in' => $token->expires - time()
        ];
    }
}
