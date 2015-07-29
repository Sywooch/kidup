<?php
use app\modules\item\widgets\GoogleAutoComplete;

$this->registerJs("
window.filterData['location'] = {
    type: 'string',
    value: \"" . $searchModel->location . "\"
};
");

$this->registerJs("
window.filterData['distance'] = {
    type: 'integer',
    value: \"" . $searchModel->distance . "\"
};
");

$this->registerJs("updateDistanceValue(" . $searchModel->distanceIndex . ");");
?>
<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">
                <?php if ($collapsable) { ?>
                <a data-toggle="collapse" href="#refine-location">
                    <?php } ?>
                    <?= Yii::t("item", "Location") ?>
                    <?php if ($collapsable) { ?>
                    <i class="fa fa-caret-up pull-right"></i>
                </a>
            <?php } ?>
            </h6>
        </div>
        <div id="refine-location" class="panel-collapse collapse in">
            <div class="panel-body">
                <?= $form->field($searchModel, 'location')->label(false)
                    ->widget(GoogleAutoComplete::classname(), [
                        'options' => [
                            'class' => 'form-control',
                            "placeholder" => 'Location',
                            'filter' => "location",
                            'domain' => "string",
                            'id' => 'location' . ($mobile ? 'Mobile' : '')
                        ],
                        'autocompleteOptions' => [
                            'types' => ['geocode']
                        ]
                    ]); ?>
                <br>
                <?php
                if (!$mobile) {
                ?>
                                        <span class="price price-right"><?= Yii::t("item", "Distance: ") ?>
                                            <span
                                                class="distanceValue"><?= $searchModel->convertDistance($searchModel->distance) ?></span>
                                        </span>

                <div class="clearfix"></div>
                <?= $form->field($searchModel,
                    'distanceIndex')->label(false)->widget(\yii\jui\SliderInput::classname(), [
                    'clientOptions' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                        'value' => 0,
                        'id' => 'slider-default',
                        'slide' => new yii\web\JsExpression(<<<JS
                                                function(event, ui) {
                                                    updateDistanceValue(ui.value);
                                                }
JS
                        ),
                        'change' => new yii\web\JsExpression(<<<JS
                            function(event, ui) {
                                var value = 0;
                                if (ui.value <= 1) {
                                    value = ui.value;
                                } else if (ui.value <= 2) {
                                    value = 10 + 10 * (ui.value - 2)
                                } else if (ui.value < 3) {
                                    value = 100 + 100 * (ui.value - 3);
                                } else if (ui.value == 3) {
                                    value = -1;
                                }
                                if (value != -1) {
                                    value = value * 1000;
                                    value = Math.round(value);
                                }
                                window.changeDetected(true, 'distance', 'integer', value);
                            }
JS
                        )
                    ],
                ]) ?>
                <?php
                } else {
                    ?>
                <span class="price price-right"><?= Yii::t("item", "Distance: ") ?>
                    <div class="input-group">
                        <input type="text" filter="distance" domain="integer" class="form-control" placeholder="All" aria-describedby="basic-addon2">
                        <span class="input-group-addon" id="basic-addon2">km</span>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>