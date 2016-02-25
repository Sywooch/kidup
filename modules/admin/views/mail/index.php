<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'E-mail templates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'template',
                'value' => 'template',
                'format' => 'raw',
                'value' => function ($data) {
                    return '<a href="' . Url::to('admin/mail/view?id=' . $data['template'], true) . '" target="_blank">' . $data['template'] . '</a>';
                }
            ],
            [
                'attribute' => 'variables',
                'value' => 'variables',
                'format' => 'raw',
                'value' => function ($data) {
                    return join(', ', $data['variables']);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function() {
                        return '';
                    },
                    'delete' => function() {
                        return '';
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            Yii::$app->urlManager->createUrl(['admin/mail/view', 'id' => $model['template']]), [
                                'title' => 'View',
                            ]);
                    }

                ],
            ],
        ]
    ]); ?>

</div>
