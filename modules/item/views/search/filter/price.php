<?php
$this->registerJs("
window.filterData['priceMin'] = {
    type: 'integer',
    value: \"" . $searchModel->priceMin . "\"
};
");

$this->registerJs("
window.filterData['priceMax'] = {
    type: 'integer',
    value: \"" . $searchModel->priceMax . "\"
};
");
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?php if ($collapsable) { ?>
            <a data-toggle="collapse" href="#refinePrice" class="collapsed">
                <?php } ?>
                Price (Weekly)
                <?php if ($collapsable) { ?>
                <i class="fa fa-caret-up pull-right"></i>
            </a>
            <?php } ?>
        </h6>
    </div>
    <div id="refinePrice" class="panel-collapse collapse in">
        <div class="panel-body">
            <?php
            if (!$mobile) {
                ?>
                <span class="price price-left min-price">DKK <?= $searchModel->priceMin ?></span>
                <span class="price price-right max-price">DKK <?= $searchModel->priceMax ?></span>

                <input type="hidden" name="priceMin" filter="priceMin" domain="integer" />
                <input type="hidden" name="priceMax" filter="priceMax" domain="integer" />

                <div class="clearfix"></div>
                <?= $form->field($searchModel, 'priceRange')
                    ->label(false)
                    ->widget(\yii\jui\SliderInput::classname(), [
                        'clientOptions' => [
                            'min' => 0,
                            'max' => 999,
                            'step' => 10,
                            'values' => [$searchModel->priceMin, $searchModel->priceMax],
                            'range' => true,
                            'slide' => new yii\web\JsExpression("function(event, ui) {
                                                        $('.min-price').text('DKK '+ ui.values[0] );
                                                        $('.max-price').text('DKK '+ ui.values[1] );
                                                        $('#pricerange').val( ui.values[0]+','+ui.values[1]);
                                                    }"),
                            'change' => new yii\web\JsExpression("function(event, ui) {
                                                        window.changeDetected(true, 'priceMin', 'integer', ui.values[0]);
                                                        window.changeDetected(true, 'priceMax', 'integer', ui.values[1]);
                                                        }"),
                        ]
                    ])
                ?>
            <?php
            } else {
                ?>
                <div class="input-group">
                    <input type="text" filter="priceMin" domain="integer" class="form-control" placeholder="0" aria-describedby="basic-addon2">
                    <span class="input-group-addon" id="basic-addon2">DKK</span>
                </div>
                <br />
                <div class="input-group">
                    <input type="text" filter="priceMax" domain="integer" class="form-control" placeholder="999" aria-describedby="basic-addon2">
                    <span class="input-group-addon" id="basic-addon2">DKK</span>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>