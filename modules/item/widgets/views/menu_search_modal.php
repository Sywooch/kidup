<?php
use yii\helpers\Url;

?>

<!-- Search modal -->
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-inline hidden-xs" method="get">
                <div class="form-group">
                    <input type="text"
                           class="form-control mobile-search-query"
                           placeholder="<?= \Yii::t('item', 'What are you looking for?') ?>">
                </div>
                <button class="btn btn-fill btn-danger" type="button"
                        onclick="window.location.href = '<?= Url::base(true) ?>/search?q=query|'+$('.mobile-search-query').val()">
                    >
                    <?= Yii::t("item", "Search") ?>
                </button>
            </form>
        </div>
    </div>
</div>