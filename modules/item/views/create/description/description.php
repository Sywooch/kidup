<h4><?= Yii::t("item", "Title") ?><br>
    <small><?= Yii::t("item",
            "How can you describe your item, in one catchy title?") ?></small>
</h4>
<div class="form-group">
    <?= $form->field($model,
        'name')->textInput(['class' => 'form-control'])->label(false) ?>
</div>

<h4><?= Yii::t("item", "Description") ?>
    <br>
    <small>
        <?= Yii::t("item",
            "How much is the item used? What do you enjoy so much about it? What parent-child moments did you experience with this item?") ?>
    </small>
</h4>
<?= $form->field($model,
    'description')->textarea([
    'class' => 'form-control',
    'rows' => 6
])->label(false) ?>