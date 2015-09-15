<?php
use app\modules\item\widgets\ItemCard;
// displaying the search results
?>
<div class="searchResults" id="results">
    <div class="row">
        <?php
        foreach ($results as $result) {
            echo ItemCard::widget([
                'model' => $result,
                'showDistance' => true,
                'numberOfCards' => 3
            ]);
        }
        if (count($results) === 0) {
            ?><p><?php
            echo Yii::t('item', 'No products were found.');
            ?></p><?php
        }
        ?>
    </div>
</div>
