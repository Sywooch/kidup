<?php
if (!isset($results)) $results = [];

// displaying the search results
echo '<div class="searchResults">';

echo '</div>';
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
