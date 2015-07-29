<?php
use yii\helpers\Html;

$this->registerJs("
window.filterData['age'] = {
    type: 'set',
    value: [" . join(", ", $searchModel->age) . "]
}
");
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?php if ($collapsable) { ?>
            <a data-toggle="collapse" href="#refineAge" class="collapsed">
            <?php } ?>
                <?= Yii::t("item", "Age") ?>
            <?php if ($collapsable) { ?>
                <i class="fa fa-caret-up pull-right"></i>
            <?php } ?>
            </a>
        </h6>
    </div>
    <div id="refineAge" class="panel-collapse collapse in">
        <div class="panel-body">
            <?php foreach ($categories as $category): if ($category['type'] != 'age') {
                continue;
            }
                $selectedClass = '';
                $value = 0;
                if (in_array($category->id, $searchModel->age)) {
                    $selectedClass = 'btn-fill btn-primary';
                    $value = 1;
                }
                ?>
                <div class="btn btn-default btn-xs category-clickable-button smallBottomMargin
                    <?= $selectedClass ?>"
                     data-id="<?= $category->id ?>" filter="age" domain="set">
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