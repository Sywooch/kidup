<?php

namespace app\modules\review\widgets;

use app\components\Cache;
use app\modules\review\models\Review;
use Yii;
use yii\helpers\Html;
class ReviewScore extends \yii\bootstrap\Widget
{
    public $user_id = null;
    public $stars;
    public $reviewCount = false;
    public $onEmpty = false;

    public function run()
    {
        if(!$this->onEmpty){
            $this->onEmpty = Html::tag('div', \Yii::t('review.widget.no_reviews_yet', 'No reviews yet'));
        }
        if(!is_null($this->user_id)){
            $reviews = Review::find()->where([
                'is_public' => 1,
                'reviewed_id' => $this->user_id
            ])->andWhere(['IN', 'value', [1, 2, 3, 4, 5]])->select('value')->asArray()->all();
            $scores = [];
            foreach ($reviews as $review) {
                $scores[] = $review['value'];
            }

            if (count($scores) == 0) {
                return $this->onEmpty;
            }

            $stars = round(array_sum($scores) / count($scores));
        }else{
            $stars = $this->stars;
        }

        if($this->reviewCount && !is_null($this->user_id)){
            $this->reviewCount = Review::find()->where(['reviewed_id' => $this->user_id])
                ->andWhere('type = :t1')->params([
                    ':t1' => Review::TYPE_USER_PUBLIC
                ])->count();
        }else{
            $this->reviewCount = false;
        }

        return $this->render('review_score', ['stars' => $stars, 'model' => $this]);
    }
}

