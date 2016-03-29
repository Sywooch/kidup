<?php
namespace codecept\functional\system;

use codecept\_support\MuffinHelper;
use codecept\_support\MuffinTrait;
use codecept\muffins\UserMuffin;
use functionalTester;
use League\FactoryMuffin\FactoryMuffin;
use user\models\User;
use yii\db\ActiveRecord;

/**
 * functional test for events.
 *
 * Class EventCest
 * @package codecept\functional\system
 */
class EventCest
{

    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function checkActiveRecordHooks(functionalTester $I) {
        $num_before_insert_triggers = 0;
        $num_after_insert_triggers = 0;
        $num_before_update_triggers = 0;
        $num_after_update_triggers = 0;
        $num_before_delete_triggers = 0;
        $num_after_delete_triggers = 0;

        \yii\base\Event::on(User::className(), ActiveRecord::EVENT_BEFORE_INSERT, function($event) use (&$num_before_insert_triggers) {
            $num_before_insert_triggers += 1;
        });

        \yii\base\Event::on(User::className(), ActiveRecord::EVENT_AFTER_INSERT, function($event) use (&$num_after_insert_triggers) {
            $num_after_insert_triggers += 1;
        });

        \yii\base\Event::on(User::className(), ActiveRecord::EVENT_BEFORE_UPDATE, function($event) use (&$num_before_update_triggers) {
            $num_before_update_triggers += 1;
        });

        \yii\base\Event::on(User::className(), ActiveRecord::EVENT_AFTER_UPDATE, function($event) use (&$num_after_update_triggers) {
            $num_after_update_triggers += 1;
        });

        \yii\base\Event::on(User::className(), ActiveRecord::EVENT_BEFORE_DELETE, function($event) use (&$num_before_delete_triggers) {
            $num_before_delete_triggers += 1;
        });

        \yii\base\Event::on(User::className(), ActiveRecord::EVENT_AFTER_DELETE, function($event) use (&$num_after_delete_triggers) {
            $num_after_delete_triggers += 1;
        });

        $this->fm = (new MuffinHelper())->init();
        $user = $this->fm->create(UserMuffin::class, [
            'password_hash' => \Yii::$app->security->generatePasswordHash('testtest')
        ]);

        $num_updates_before = $num_before_update_triggers;
        $num_updates_after = $num_after_update_triggers;

        $I->assertEquals(1, $num_after_insert_triggers, 'after_insert');
        $I->assertEquals(1, $num_before_insert_triggers, 'before_insert');
        $I->assertEquals(0, $num_after_delete_triggers, 'after_delete');
        $I->assertEquals(0, $num_before_delete_triggers, 'before_delete');

        $faker = \Faker\Factory::create();
        $user->email = $faker->freeEmail;
        $I->assertTrue($user->save());
        $I->assertEquals(1, $num_after_insert_triggers, 'after_insert');
        $I->assertEquals(1, $num_before_insert_triggers, 'before_insert');
        $I->assertEquals($num_updates_after + 1, $num_after_update_triggers, 'after_update');
        $I->assertEquals($num_updates_before + 1, $num_before_update_triggers, 'before_update');
        $I->assertEquals(0, $num_after_delete_triggers, 'after_delete');
        $I->assertEquals(0, $num_before_delete_triggers, 'before_delete');

        $user->delete();
        $I->assertEquals(1, $num_after_insert_triggers, 'after_insert');
        $I->assertEquals(1, $num_before_insert_triggers, 'before_insert');
        $I->assertEquals($num_updates_after + 1, $num_after_update_triggers, 'after_update');
        $I->assertEquals($num_updates_before + 1, $num_before_update_triggers, 'before_update');
        $I->assertEquals(1, $num_after_delete_triggers, 'after_delete');
        $I->assertEquals(1, $num_before_delete_triggers, 'before_delete');
    }

}
?>