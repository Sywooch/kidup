<?php
namespace codecept\api\language;

use admin\models\I18nMessage;
use admin\models\I18nSource;
use ApiTester;
use codecept\_support\MuffinHelper;
use codecept\_support\NotificationHelper;
use codecept\muffins\I18nMessageMuffin;
use codecept\muffins\I18nSourceMuffin;
use codecept\muffins\UserMuffin;
use League\FactoryMuffin\FactoryMuffin;
use notification\components\NotificationDistributer;
use user\models\user\User;

/**
 * Testing whether the language is set correctly.
 *
 * Class LanguageCest
 * @package codecept\api\language
 */
class LanguageCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }

    public function testMailLanguage(ApiTester $I)
    {
        I18nMessage::deleteAll();
        I18nSource::deleteAll();
        $message = $this->fm->create(I18nSourceMuffin::class, [
            'category' => 'mail.user_reconfirm.title',
            'message' => 'Is this the real mail?'
        ]);
        $this->fm->create(I18nMessageMuffin::class, [
            'id' => $message->id,
            'language' => 'da-DK',
            'translation' => 'mail_text_2'
        ]);

        // Create a user
        User::deleteAll(['email' => 'test@user.com']);
        $user = $this->fm->create(UserMuffin::class, [
            'email' => 'test@user.com'
        ]);

        // First check the Danish version
        $user->profile->language = 'da-DK';
        $user->save();

        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($user->id))->userReconfirm($user);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        $I->assertContains('mail_text_2', $mailView);

        // Now check the English version (default text)
        $user->profile->language = 'en';
        $user->profile->first_name = 'Firstname';
        $user->profile->last_name = 'Lastname';
        echo $user->profile->save();

        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($user->id))->userReconfirm($user);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        $I->assertContains('Is this the real mail?', $mailView);
    }

}
