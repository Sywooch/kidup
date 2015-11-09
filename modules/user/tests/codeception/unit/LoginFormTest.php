<?php

namespace user\tests;

use Codeception\Specify;
use user\forms\Login;
use yii\codeception\TestCase;

class LoginFormTest extends TestCase
{
    use Specify;

    public $appConfig = '@app/modules/user/tests/codeception/config/unit.php';

    /**
     * @var Login
     */
    protected $form;


    public function testLogin()
    {
        $this->form = \Yii::createObject(Login::className());

        $this->specify('should not allow logging in blocked users', function () {

            $user = $this->getFixture('user')->getModel('blocked');
            $this->form->setAttributes([
                'login' => $user->email,
                'password' => 'qwerty'
            ]);
            $this->assertFalse($this->form->validate());
            $this->assertContains('Your account has been blocked', $this->form->getErrors('login'));
        });


        $this->specify('should log the user in with correct credentials', function () {
            $user = $this->getFixture('user')->getModel('admin');
            $this->form->setAttributes([
                'login' => $user->email,
                'password' => 'wrong'
            ]);
            $this->assertFalse($this->form->validate());
            $this->form->setAttributes([
                'login' => $user->email,
                'password' => 'testtest'
            ]);
            $this->assertTrue($this->form->validate());
        });
    }
}
