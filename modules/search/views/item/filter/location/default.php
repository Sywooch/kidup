<?php
use app\modules\item\widgets\GoogleAutoComplete;
?>

<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title ">
                <?= Yii::t("item", "Location") ?>
            </h6>
        </div>
        <div class="panel-collapse">
            <div class="panel-body">
                <?=
                $form->field($model, 'location')->label(false)
                    ->widget(GoogleAutoComplete::classname(), [
                        'options' => [
                            'class' => 'form-control',
                            "placeholder" => 'Location',
                            'filter' => "location",
                            'domain' => "string",
                            'id' => 'location'
                        ],
                        'autocompleteOptions' => [
                            'types' => ['geocode']
                        ]
                    ]);
                ?>
            </div>
         </div>
    </div>
</div>