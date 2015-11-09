<?php
/**
 * @var View $this
 * @var  $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var array $tabContent
 */
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

echo Breadcrumbs::widget(['links' => [
    $this->title
]]);
?>
<div class="message-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?= yii\bootstrap\Nav::widget([
        'items' => $menuItems,
        'options' => [
            'class' => 'nav-tabs'
        ]
    ]) ?>

    <?php
    echo GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'source',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return Html::a($model->i18nSource->message, ['update', 'id' => $model->i18nSource->id], ['data' => ['pjax' => 0]]);
                }
            ],
            [
                'class' => kartik\grid\EditableColumn::className(),
                'attribute' => 'translation',
                'editableOptions' => [
                    'size' => 'md',
                    'placement' => \kartik\popover\PopoverX::ALIGN_TOP,
                    'inputType' => \kartik\editable\Editable::INPUT_TEXTAREA,
                    'formOptions' => [
                        'action' => \yii\helpers\Url::to(['save-translate'])
                    ],
                    'pluginEvents' => [
                        "editableSuccess" => "function(val) {
                            var id = val.currentTarget.id;
                            var row = $('#'+id).closest('tr');
                            row.next().find('.kv-editable-link').trigger('click').parent().find('.kv-editable-input').focus();
                        }",
                    ]
                ]
            ],
            [
                'attribute' => 'category',
                'value' => function($data) {
                    return $data->i18nSource->category;
                }
            ],
            [
                'attribute' => 'translationStatus',
                'value' => function($data) {
                    return empty($data->translation) ? 'no' : 'yes';
                },
                'filter' => [
                    'yes' =>  'yes',
                    'no' =>  'no',
                ],
                'contentOptions' => [
                    'class' => 'text-center'
                ]
            ],
        ]
    ]);
    ?>
</div>