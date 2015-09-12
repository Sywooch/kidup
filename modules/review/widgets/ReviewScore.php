<?php

namespace app\modules\review\widgets;

use app\components\Cache;
use app\modules\review\models\Review;
use Yii;

class ReviewScore extends \yii\bootstrap\Widget
{
    public $user_id;

    public function run()
    {
        $reviews = Review::find()->where([
            'is_public' => 1,
            'reviewed_id' => $this->user_id
        ])->andWhere(['IN', 'value', [1, 2, 3, 4, 5]])->select('value')->asArray()->all();
        $scores = [];
        foreach ($reviews as $review) {
            $scores[] = $review['value'];
        }

        if (count($scores) == 0) {
            return \Yii::t('review', 'No reviews yet');
        }

        $stars = round(array_sum($scores) / count($scores));
        return $this->render('review_score', ['stars' => $stars]);
    }
}

