<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \admin\models\search\Item $searchModel
 */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Item', ['create'], ['class' => 'btn btn-success'])*/ ?>
    </p>

    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'value' => function ($model) {
                    return Html::a($model->name, "@web/admin/item/view?id=" . $model->id);
                },
                'format' => 'raw'
            ],
            'price_week',
            [
                'attribute' => 'owner_id',
                'value' => function ($model) {
                    return Html::a($model->owner->email, '@web/admin/user/view?id=' . $model->owner->id);
                },
                'format' => 'raw'
            ],
            'is_available:boolean',
            'created_at:datetime',
            [
                'attribute' => 'media',
                'value' => function ($model) {
                    $str = '';
                    foreach ($model->media as $i => $media) {
                        if ($i >= 2) {
                            break;
                        }
                        $str .= Html::img(\images\components\ImageHelper::url($media->file_name, ['w' => 120]),
                            ['width' => '120px']);
                    }
                    return $str;
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'category_id',
                'value' => function ($model) {
                    return $model->category->name;
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'publish_action',
                'value' => function ($model) {
                    if($model->isAvailable()){
                        return Html::a("@web/admin/item/unpublish?id=".$model->id);
                    }else{
                        return Html::a("@web/admin/item/publish?id=".$model->id);
                    }
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            Yii::$app->urlManager->createUrl(['admin/item/view', 'id' => $model->id, 'edit' => 't']), [
                                'title' => 'Edit',
                            ]);
                    },
                ],
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'],
                ['class' => 'btn btn-success']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'],
                ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
    ]);
    Pjax::end(); ?>

</div>
