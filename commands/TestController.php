<?php

namespace app\commands;

use admin\models\I18nMessage;
use admin\models\I18nSource;
use booking\models\Booking;
use booking\models\Invoice;
use booking\models\Payin;
use booking\models\Payout;
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
use mail\models\MailAccount;
use mail\models\MailMessage;
use mail\models\Token;
use message\models\Conversation;
use message\models\Message;
use review\models\Review;
use search\models\ItemSearch;
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
use yii\console\Controller;

class TestController extends Controller
{
    public function actionReview()
    {
        self::createReviews(4,6, "Helt perfekt! Alexander var super god til at vise hvordan klapvognen virkede og standen var helt som ny! Så god service!!");
        self::createReviews(4,7, "Alexander var super!");
        self::createReviews(4,8, "");

        self::createReviews(7,16, "Perfekt!");
        self::createReviews(7,17, "Christoffer var super!");
        self::createReviews(7,18, "");

        self::createReviews(160,41, "Nanna var super sød. Vi lejede hendes Klapvogn i en forlænget weekend - og fik alle nødvendige instrukser inden vi tog den i brug i Aarhus' gader! Perfekt");
        self::createReviews(160,44, "Nøj hvor er det smart! Vi lejede en Nanna's bærerygsæk da vi skulle på ferie! Alt gik som smurt og den levede virkelig op til forventningerne!");

        self::createReviews(161,43, "Nanna var super sød. Vi lejede hendes Klapvogn i en forlænget weekend - og fik alle nødvendige instrukser inden vi tog den i brug i Aarhus' gader! Perfekt");
        self::createReviews(161,46, "Nøj hvor er det smart! Vi lejede en Nanna's bærerygsæk da vi skulle på ferie! Alt gik som smurt og den levede virkelig op til forventningerne!");
    }

    private static function createReviews($userId, $itemId, $text){
        self::create(Review::TYPE_USER_PUBLIC, $text, $itemId, $userId);
        self::create(Review::TYPE_EXPERIENCE, rand(4,5), $itemId, $userId);
        self::create(Review::TYPE_USER_COMMUNICATION, rand(4,5), $itemId, $userId);
        self::create(Review::TYPE_USER_EXCHANGE, rand(4,5), $itemId, $userId);
        self::create(Review::TYPE_AD_ACCURACY, rand(4,5), $itemId, $userId);
    }

    private static function create($type, $value, $itemId, $userId)
    {
        $review = new Review();
        $review->value = (string)$value;
        $review->type = $type;
        $review->item_id = $itemId;
        $review->reviewer_id = 1;
        $review->booking_id = 0;
        $review->reviewed_id = $userId;
        $review->is_public = 1;
        return $review->save();
    }

}
