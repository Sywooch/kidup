<?php

namespace booking\tests;

use booking\widgets\CreateBooking;
use Carbon\Carbon;
use Codeception\Specify;
use item\forms\Create;
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
            $this->assertTrue($this->form->validate());
        });
    }
}
