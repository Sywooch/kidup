<?php

namespace app\commands;

use admin\models\I18nMessage;
use admin\models\I18nSource;
use api\models\oauth\OauthAccessToken;
use app\components\Cache;
use booking\models\Booking;
use booking\models\Invoice;
use booking\models\Payin;
use booking\models\Payout;
use codecept\muffins\OauthClient;
use item\models\base\CategoryHasFeature;
use item\models\base\Feature;
use item\models\base\FeatureValue;
use item\models\base\ItemHasFeature;
use item\models\base\ItemHasFeatureSingular;
use item\models\base\JobQueue;
use item\models\Category;
use item\models\Item;
use item\models\ItemHasMedia;
use item\models\ItemSimilarity;
use item\models\Media;
use League\FactoryMuffin\Faker\Faker;
use mail\models\MailAccount;
use mail\models\MailMessage;
use mail\models\Token;
use message\models\Conversation;
use message\models\Message;
use review\models\Review;
use search\components\ItemSearchDb;
use search\models\ItemSearch;
use user\forms\Registration;
use user\models\base\Currency;
use user\models\base\IpLocation;
use user\models\base\PromotionCode;
use user\models\base\UserHasPromotionCode;
use user\models\Country;
use user\models\Language;
use user\models\Location;
use user\models\PayoutMethod;
use user\models\Profile;
use user\models\Setting;
use user\models\SocialAccount;
use user\models\User;
use Yii;
use yii\caching\TagDependency;
use yii\console\Controller;

class ProductController extends Controller
{
    public function actionUsers()
    {
        \Yii::$app->mailer->useFileTransport = true;
        $names = json_decode(file_get_contents(Yii::$aliases['@app'] . '/devops/fake-products/names.json'), true);
        foreach ($names['males'] as $male) {
            $registration = new Registration();
            $registration->email = "fake-user-" . rand(0, 100000000) . "@kidup.dk";
            $registration->password = "fakeuserfromkidup";
            if ($registration->register()) {
                $user = User::findOne(['email' => $registration->email]);
                $user->role = 666;
                $user->flags = 1;
                $user->created_at = time() - rand(0, 100098451);
                $user->save();
                // should have worked
                $user->profile->first_name = explode(" ", $male)[0];
                $user->profile->last_name = explode(" ", $male)[1];
                $user->profile->save();
            }
        }

        foreach ($names['females'] as $female) {
            $registration = new Registration();
            $registration->email = "fake-user-" . rand(0, 100000000) . "@kidup.dk";
            $registration->password = "fakeuserfromkidup";
            if ($registration->register()) {
                $user = User::findOne(['email' => $registration->email]);
                $user->role = 666;
                $user->flags = 2;
                $user->created_at = time() - rand(0, 100098451);
                $user->save();
                // should have worked
                $user->profile->first_name = explode(" ", $female)[0];
                $user->profile->last_name = explode(" ", $female)[1];
                $user->profile->save();
            }
        }
    }

    public function actionItems()
    {
        $dir = scandir(Yii::$aliases['@runtime'] . '/correct');

        $items = [];
        foreach ($dir as $i => $d) {
            if (strpos($d, ".json") == false) {
                continue;
            }

            $product = json_decode(file_get_contents(Yii::$aliases['@runtime'] . '/correct/' . $d), true);

            if (isset($product['kidup_id'])) {
                continue;
            }

            $owner = User::find()->where(['role' => 666])->orderBy("RAND()")->one();

            if ($owner->locations[0]->latitude == 0 || $owner->locations[0]->latitude == 1) {
                $fakeLoc = Location::find()->where('longitude != 0 and longitude != 1')->orderBy("rand()")->one();

                $location = $owner->locations[0];
                $location->setAttributes([
                    'country' => 1,
                    'city' => 'fAarhus',
                    'zip_code' => "8000",
                    'street_name' => 'fake_street',
                    'street_number' => "1",
                    'longitude' => $fakeLoc->longitude * (1 + rand(-1, 1) / 1000),
                    'latitude' => $fakeLoc->latitude * (1 + rand(-1, 1) / 1000),
                    'user_id' => $owner->id
                ]);
                $location->save(false);
            }

            $item = new Item();
            $name = trim(explode("-", $product['title_name'])[0]);
            if (rand(0, 10) > 5) {
                $name = ucfirst(strtolower($name));
            }
            $item->setAttributes([
                'name' => $name,
                'description' => $product['description'],
                'category_id' => $product['kidup_category'],
                'owner_id' => $owner->id,
                'min_renting_days' => 666,
                'is_available' => 0,
                'currency_id' => 1,
                'price_day' => max(round(0.01 * $product['reg_price']), rand(4, 8)),
                'price_week' => max(round(0.03 * $product['reg_price']), rand(8, 35)),
                'price_month' => max(round(0.06 * $product['reg_price']), rand(25, 60)),
                'price_year' => max(round(0.15 * $product['reg_price']), rand(60, 150)),
                'location_id' => $owner->locations[0]->id,
                'created_at' => time() - rand(0, 10000000),
                'updated_at' => time()
            ]);

            if ($item->save()) {
                $product['kidup_id'] = $item->id;
                file_put_contents(Yii::$aliases['@runtime'] . '/correct/' . $d, json_encode($product));
                $item->created_at = time() - rand(0, 60098451);
                $item->save();
            }
        }
    }

    public function actionSync()
    {
        $items = Item::find()->all();
        (new ItemSearchDb())->sync($items);
    }

    public function actionSyncImages()
    {
        $dir = scandir(Yii::$aliases['@runtime'] . '/correct');

        $oauthClient = \api\models\oauth\OauthClient::find()->one();
        foreach ($dir as $i => $d) {
            if (strpos($d, ".json") == false) {
                continue;
            }

            $product = json_decode(file_get_contents(Yii::$aliases['@runtime'] . '/correct/' . $d), true);

            if (!isset($product['kidup_id'])) {
                continue;
            }

            foreach ($product['image_links'] as $imgLink) {
                $f = Yii::$aliases['@runtime'] . "/parsed-images/" . md5($imgLink) . ".jpg";
                $owner = Item::findOne(['id' => $product['kidup_id']])->owner;
                var_dump($f);

                if (is_file($f)) {

                    if (count($owner->validOauthAccessTokens) == 0) {
                        $token = OauthAccessToken::make($owner, $oauthClient);
                    } else {
                        $token = $owner->validOauthAccessTokens[0];
                    }
                    $r = $this->uploadFile($f, $product['kidup_id'], $token->access_token);
                }
            }
        }
    }

    private function uploadFile($file, $itemId, $accessToken)
    {
        $target_url = 'http://192.168.33.99/api/v1/media?access-token=' . $accessToken . '&item_id=' . $itemId;
        //This needs to be the full path to the file you want to send.
        /* curl will accept an array here too.
         * Many examples I found showed a url-encoded string instead.
         * Take note that the 'key' in the array will be the key that shows up in the
         * $_FILES array of the accept script. and the at sign '@' is required before the
         * file name.
         */
        $post = array('file' => curl_file_create($file, 'image/jpeg', 'cattle-01.jpg'));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        try{
            $res = json_decode($result);
            if(is_int($res->id)){
                return true;
            }
        }catch(\yii\base\ErrorException $e){

        }
        return false;
    }
}
