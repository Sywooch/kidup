<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title ">
                <?= Yii::t("item", "Search term") ?>
            </h6>
        </div>
        <div class="panel-collapse">
            <div class="panel-body">
                <?=
                $form
                    ->field($model, 'query')
                    ->label(false)
                    ->textInput([
                        'name' => 'query',
                        'placeholder' => Yii::t("item", "Search term")
                    ])
                ?>
            </div>
         </div>
    </div>
</div>