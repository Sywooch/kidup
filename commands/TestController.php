<?php

namespace app\commands;

use admin\models\I18nMessage;
use admin\models\I18nSource;
use app\components\Cache;
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
use notifications\models\MailAccount;
use notifications\models\MailMessage;
use notifications\models\Token;
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
use yii\caching\TagDependency;
use yii\console\Controller;

class TestController extends Controller
{


}
