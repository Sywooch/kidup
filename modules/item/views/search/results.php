<?php
// displaying the search results
echo '<div id="searchResults">';
echo \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => 'searchitem',
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