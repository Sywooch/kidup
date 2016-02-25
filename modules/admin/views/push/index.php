<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Push notifications';
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
                'format' => 'raw',
                'value' => 'template'
            ],
            [
                'attribute' => 'message',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data['message'] == null) return null;
                    return '<pre>' . $data['message'] . '</pre>';
                }
            ],
            [
                'attribute' => 'fallback',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data['fallback'] == null) return null;
                    return '<a href="' . Url::to('admin/mail/view?id=' . $data['fallback'], true) . '" target="_blank">' . $data['fallback'] . '</a>';
                }
            ],
            [
                'attribute' => 'variables',
                'format' => 'raw',
                'value' => function ($data) {
                    return join(', ', $data['variables']);
                }
            ]
        ]
    ]); ?>

</div>
