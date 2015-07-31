<?php
if (!isset($results)) $results = [];

// displaying the search results
echo '<div class="searchResults">';
echo \yii\widgets\ListView::widget([
    'dataProvider' => $results,
    'itemView' => 'item',
    'layout' => '<div class="row">
                                    {items}
                                </div>
                                <div class="row">
                                    {pager}
                                </div>',
    'itemOptions' => ['tag' => 'span'],
]);
echo '</div>';
?>