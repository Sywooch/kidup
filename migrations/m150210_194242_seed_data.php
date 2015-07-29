<?php

use yii\db\Migration;

class m150210_194242_seed_data extends Migration
{
    public function up()
    {
        $this->batchInsert('currency', ['id', 'name', 'abbr', 'forex_name'], [[1, 'Danish Kroner', 'kr', 'DKK']]);

        $this->batchInsert('country', ['id', 'code', 'name', 'main_language_id', 'currency_id', 'phone_prefix', 'vat'],
            [
                [1, 'DK', 'Denmark', 'da-DK', 1, 45, 25.00],
//            [1, 'NL', 'Netherlands', 'nl-NL', 1,31],
            ]);

        $this->batchInsert('category', ['id', 'type', 'name'], [
            [1, 'age', '< 6 months'],
            [2, 'age', '6-12 months'],
            [3, 'age', '1-2 years'],
            [4, 'age', '2-4 years'],
            [5, 'age', '4-6 years'],
            [6, 'age', '6-9 years'],
            [7, 'age', '9+ years'],
            [10, 'main', 'Furniture'],
            [11, 'main', 'Safety Seats'],
            [12, 'main', 'Toys'],
            [13, 'main', 'Strollers'],
            [14, 'main', 'Safety'],
            [15, 'main', 'Parents'],
            [16, 'main', 'Cribs & Beds'],
            [17, 'main', 'Sports & Games'],
            [18, 'main', 'Travelling'],
            [19, 'main', 'Electronic'],
            [20, 'main', 'Bikes'],
            [21, 'main', 'Packages'],
            [22, 'main', 'Outdoor'],
            [23, 'main', 'Clothing'],
            [24, 'main', 'Other'],
            [100, 'special', 'Pet-free home'],
        ]);

        $user = new \app\modules\user\models\User();
        $user->setScenario('create');
        $user->setAttributes(array(
            'id' => 1,
            'email' => 'simon@kidup.dk',
            'password_hash' => '$2y$13$NDOMQAPtRQoORtVBcX/7GuTExkqb.QfCWi8E0VRnvK2f/cDC2xxUS',
            'auth_key' => 'NyPoDgo9hIUuoGtk5pQjcAfvxFYu_mUy',
            'confirmed_at' => 1432405898,
            'unconfirmed_email' => null,
            'blocked_at' => null,
            'registration_ip' => '217.28.166.140',
            'flags' => 0,
            'status' => 0,
            'role' => 0,
            'created_at' => 1432405898,
            'updated_at' => 1432405898,
        ));
        $user->save();
        $profile = new \app\modules\user\models\Profile();
        $profile->setAttributes([
            'user_id' => 1,
            'description' => 'KidUp Admin - ask me anything!',
            'first_name' => 'KidUp',
            'last_name' => 'Admin',
            'img' => \app\modules\item\components\MediaManager::DEFAULT_USER_IMG,
            'phone_country' => null,
            'phone_number' => null,
            'email_verified' => 1,
            'phone_verified' => 1,
            'identity_verified' => 1,
            'location_verified' => 1,
            'language' => null,
            'currency_id' => 1,
            'nationality' => null,
        ]);
        $profile->save();

    }

    public function down()
    {
        echo "m150209_194242_seed_data cannot be reverted.\n";

        return false;
    }
}
