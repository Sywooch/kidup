<h4>
    <?= Yii::t("item", "Tell parents about your product") ?>
</h4>
<?= Yii::t("item", "Every product on KidUp is unique: what makes your product stand out?") ?>
<hr>
<div class="form-group">
    <?= $form->field($model,
        'name')->textInput([
        'class' => 'form-control',
        'placeholder' => \Yii::t('item', 'Be clear and descriptive')
    ])->label(\Yii::t('item', 'Product name')) ?>
</div>

<?= $form->field($model,
    'description')->textarea([
    'class' => 'form-control',
    'rows' => 6,
    'placeholder' => \Yii::t('item', 'Tell parents what you love about the product. You can include any details that makes it use practicle in daily life.')
])->label(\Yii::t('item', 'Summary')) ?>