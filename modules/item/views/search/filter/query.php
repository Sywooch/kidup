<?php
use yii\helpers\Html;

$this->registerJs("
window.filterData['query'] = {
    type: 'string',
    value: \"" . $searchModel->query . "\"
}
");
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?php if ($collapsable) { ?>
            <a data-toggle="collapse" href="#refinePrice" class="collapsed">
                <?php } ?>
                Search
                <?php if ($collapsable) { ?>
                <i class="fa fa-caret-up pull-right"></i>
            </a>
            <?php } ?>
        </h6>
    </div>
    <div id="refinePrice" class="panel-collapse collapse in">
        <div class="panel-body">
            <input class="form-control" filter="query" domain="string" type="text" value="<?= $searchModel->query ?>" />
        </div>
    </div>
</div>