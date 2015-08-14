<?php

namespace app\modules\user\tests;

use app\modules\user\forms\Login;
use app\modules\user\tests\codeception\_support\FixtureHelper;
use Codeception\Specify;
use yii\codeception\TestCase;

class LoginFormTest extends TestCase
{
    use Specify;

    public $appConfig = '@app/modules/user/tests/codeception/config/unit.php';

    /**
     * @var Login
     */
    protected $form;

    public function fixtures()
    {
        return FixtureHelper::fixtures();
    }

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
