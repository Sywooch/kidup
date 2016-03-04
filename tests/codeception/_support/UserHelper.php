<?php

namespace codecept\_support;

use AcceptanceTester;
use codecept\muffins\OauthAccessTokenMuffin;
use Codeception\Util\Debug;
use FunctionalTester;
use user\models\User;
use Yii;
use yii\helpers\ArrayHelper;

class UserHelper
{

    /**
     * Login a user.
     */
    public static function login(User $user)
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            UserHelper::logout();
        }

        Yii::$app->user->login($user);
        Yii::$app->session->set('lang', 'en');
    }


    /**
     * Logout a user.
     *
     * @param  FunctionalTester|AcceptanceTester the tester
     */
    public static function logout()
    {
        \Yii::$app->user->logout();
    }

    /**
     * Returns the parameters for a user login
     */
    public static function apiLogin($user, $array = []){
        foreach ($user->validOauthAccessTokens as $validOauthAccessToken) {
            return ArrayHelper::merge($array, [
                'access-token' => $validOauthAccessToken->access_token
            ]);
        }
        // no access tokens found
        $fm = (new MuffinHelper())->init();
        $token = $fm->create(OauthAccessTokenMuffin::className(),[
            'user_id' => $user->id
        ]);
        return ArrayHelper::merge($array, [
            'access-token' => $token->access_token
        ]);
    }

}
