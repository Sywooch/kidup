<?php

namespace app\tests\codeception\_support;
use AcceptanceTester;
use app\modules\user\models\User;
use FunctionalTester;
use Yii;

class UserHelper
{

    /**
     * Login a user.
     *
     * @param $I FunctionalTester|AcceptanceTester the tester
     * @param $username string the username of the user
     * @param $password string the password of the user
     */
    public static function login($I, $username, $password) {
        if (Yii::$app->getUser()->getIsGuest()) {
            //$identity = new UserIdentity($username, $password);
            //$I->assertTrue($identity->authenticate(), 'I can not authenticate');
            $identity = User::find()->where(['email' => $username])->one();
            Yii::$app->user->login($identity);
            Yii::$app->session->set('lang', 'en');
        }
        $I->assertFalse(Yii::$app->getUser()->getIsGuest(), 'I should be logged in now, but I am not');
    }

    /**
     * Logout a user.
     *
     * @param $I FunctionalTester|AcceptanceTester the tester
     */
    public static function logout($I) {
        $I->amOnPage('/home');
        $I->canSee('Log Out');
        $I->click('Log Out');
        $I->amOnPage('/home');
        $I->dontSee('Log Out');
    }

    /**
     * @param $user
     * @param $password
     * @throws \yii\base\InvalidConfigException
     */
    public static function loginAcceptance($user, $password) {
        $user = User::find()->where(['email' => $user])->one();
        \Yii::$app->user->login($user);
    }

}
