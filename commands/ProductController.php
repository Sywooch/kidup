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

    public function actionSyncDb()
    {
        define(YII_ENV, 'prod');
        $batches = Item::find()
            ->where(['is_available' => 1])
            ->batch(10);

        foreach ($batches as $batch) {
            (new ItemSearchDb())->sync($batch);
        }
    }


    private function uploadFile($file, $itemId, $accessToken)
    {
        if ($itemId) {
            $target_url = 'https://www.kidup.dk/api/v1/media?access-token=' . $accessToken . '&item_id=' . $itemId;
        } else {
            $target_url = 'https://www.kidup.dk/api/v1/media?access-token=' . $accessToken . '&profile_pic=true';
        }
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
        try {
            $res = json_decode($result);
            if (is_int($res->id) || $res == true) {
                return true;
            } else {

            }
        } catch (\yii\base\ErrorException $e) {

        }
        return $result;
    }
}
