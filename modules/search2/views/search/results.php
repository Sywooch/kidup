<?php
// displaying the search results
?>
<div class="searchResults">
    <div class="row">
        <?php foreach($results as $result){
            echo $this->render('item', [
                'model' => $result
            ]);
} ?>
    </div>
</div>
