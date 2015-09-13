<?php

\app\modules\review\assets\ReviewScoreAsset::register($this);

$output = "<div class='user-review-stars'>";

for ($i = 0; $i < $stars; $i++) {
    $output .= '<i class="fa fa-star"></i>';
}

for ($i = 5; $i > $stars; $i--) {
    $output .= '<i class="fa fa-star-o"></i>';
}

$output .= "</div>";
echo $output;
?>