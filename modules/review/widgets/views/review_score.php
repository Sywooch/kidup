<?php

\review\assets\ReviewScoreAsset::register($this);

$output = "<div class='user-review-stars'>";

for ($i = 0; $i < $stars; $i++) {
    $output .= '<i class="fa fa-star"></i>';
}

for ($i = 5; $i > $stars; $i--) {
    $output .= '<i class="fa fa-star-o"></i>';
}
if($model->reviewCount){
    $output .= " - ".\Yii::t("review.widget.number_of_reviews", "{0} reviews", [$model->reviewCount]);
}
$output .= "</div>";
echo $output;
