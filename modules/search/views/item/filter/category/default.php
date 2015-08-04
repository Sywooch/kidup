<?php
/**
 * @var $model \app\modules\search\models\ItemModel
 */
?>

<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title ">
                <?= Yii::t("item", "Categories") ?>
            </h6>
        </div>
        <div class="panel-collapse">
            <div class="panel-body">
                <?php foreach ($model->getCategories("main") as $category): ?>
                    <div class="btn btn-xs btn-default">
                        <?= \Yii::t('item', $category->name) ?>
                    </div>
                <?php endforeach; ?>
            </div>
         </div>
    </div>
</div>