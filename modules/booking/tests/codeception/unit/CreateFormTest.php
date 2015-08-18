<?php

namespace app\modules\booking\tests;

use app\modules\booking\forms\Create;
use Carbon\Carbon;
use Codeception\Specify;
use tests\codeception\_support\FixtureHelper;
use yii\codeception\TestCase;


class CreateFormTest extends TestCase
{
    use Specify;

    /**
     * @var Create
     */
    protected $form;

    public function fixtures()
    {
        $fh = new FixtureHelper();
        return $fh->fixtures();
    }

    public function testLogin()
    {
        $this->form = \Yii::createObject(Create::className());

        $this->specify('should work', function () {

            $user = $this->getFixture('user')->getModel('renter');
            \Yii::$app->user->login($user);
            $this->form->setAttributes([
                'dateFrom' => Carbon::now()->format('d-m-Y'),
                'dateto' => Carbon::now()->addDays(25)->format('d-m-Y')
            ]);
            \yii\helpers\VarDumper::dump($this->form->getErrors(), 10, true);
            exit();
            $this->assertTrue($this->form->validate());

        });
    }
}
