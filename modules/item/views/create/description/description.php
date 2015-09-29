<h4>
    <?= Yii::t("item.create.description.header", "Tell parents about your product") ?>
</h4>
<?= Yii::t("item.create.description.sub_header", "Every product on KidUp is unique: what makes your product stand out?") ?>
<hr>
<div class="form-group">
    <?= $form->field($model,
        'name')->textInput([
        'class' => 'form-control',
        'placeholder' => \Yii::t('item.create.description.name_placeholder', 'Be clear and descriptive')
    ])->label(\Yii::t('item.create.description.name_label', 'Product name')) ?>
</div>

<?= $form->field($model,
    'description')->textarea([
    'class' => 'form-control',
    'rows' => 6,
    'placeholder' => \Yii::t('item.create.description.description_placeholder', 'Tell parents what you love about the product. You can include any details that makes its use practicle in daily life.')
])->label(\Yii::t('item.create.description.description_label', 'Summary')) ?>