<?php
// displaying the search results
?>
<div class="searchResults">
    <div class="row">
        <?php
        foreach ($results as $result) {
            echo $this->render('item', [
                'model' => $result
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
