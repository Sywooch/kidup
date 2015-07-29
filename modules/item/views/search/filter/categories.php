<?php
use yii\helpers\Html;

$this->registerJs("
window.filterData['categories'] = {
    type: 'set',
    value: [" . join(", ", $searchModel->categories) . "]
}
");
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?php if ($collapsable) { ?>
            <a data-toggle="collapse" href="#refine-categories" class="collapsed">
                <?php } ?>
                <?= Yii::t("item", "Categories") ?>
                <?php if ($collapsable) { ?>
                <i class="fa fa-caret-up pull-right"></i>
            </a>
        <?php } ?>
        </h6>
    </div>
    <div id="refine-categories" class="panel-collapse collapse in">
        <div class="panel-body panel-scroll">
            <?php foreach ($categories as $category): if ($category['type'] != 'main') {
                continue;
            }
                $selectedClass = '';
                $value = 0;
                if (in_array($category->id, $searchModel->categories)) {
                    $selectedClass = 'btn-fill btn-primary';
                    $value = 1;
                }
                ?>
                <div class="btn btn-default btn-xs category-clickable-button smallBottomMargin
                    <?= $selectedClass ?>"
                     data-id="<?= $category->id ?>" filter="categories" domain="set">
                    <?= $category->name ?>
                </div>
                <?= Html::activeHiddenInput($searchModel,
                    'categories[age][' . $category->id . ']', [
                        'value' => $value
                    ]); ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>