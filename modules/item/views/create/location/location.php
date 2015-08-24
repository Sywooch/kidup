<h4><?= Yii::t("item", "Location") ?>
    <br>
    <small>
        <?= Yii::t("item", "Where can the item be picked up? Other users can see in which neighbourhood you live, but the details are only shared after they made a booking.") ?>
    </small>
</h4>
<?= $form->field($model,
    'location_id')->widget(\kartik\select2\Select2::classname(), [
    'data' => \app\modules\user\helpers\SelectData::userLocations(),
    'options' => ['placeholder' => 'Select a Location', 'width' => '200px'],
    'pluginOptions' => [
        'allowClear' => false
    ],
])->label(false); ?>

<button type="button" class="btn btn-primary" data-toggle="modal"
        data-target="#location-modal">
    <?= Yii::t("item", "Add new location") ?>
</button>
