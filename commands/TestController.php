<?php

namespace app\commands;

use review\models\Review;
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
