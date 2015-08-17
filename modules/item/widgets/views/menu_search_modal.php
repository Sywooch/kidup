<?php
use yii\helpers\Url;

?>

<!-- Search modal -->
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-inline hidden-xs visible-sm visible-md" method="get" action="<?= Url::base(true) ?>/search">
                <div class="form-group">
                    <input type="text"
                           name="query"
                           class="form-control mobile-search-query"
                           placeholder="<?= \Yii::t('item', 'What are you looking for?') ?>">
                </div>
                <button type="submit" class="btn btn-fill btn-danger">
                    <?= Yii::t("item", "Search") ?>
                </button>
            </form>
            <form class="form-inline visible-xs hidden-sm hidden-md" method="get" action="<?= Url::base(true) ?>/search">
                <div class="input-group">
                    <input type="text"
                           name="query"
                           class="form-control mobile-search-query"
                           placeholder="<?= \Yii::t('item', 'What are you looking for?') ?>">
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-fill btn-danger">
                            <?= Yii::t("item", "Search") ?>
                        </button>
                      </span>
                </div>
            </form>
        </div>
    </div>
</div>