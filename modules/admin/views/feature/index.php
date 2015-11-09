<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\search\FeatureSearch $searchModel
 */

$this->title = 'Features';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feature-index">

    <p>
        Features are product properties (such as gender, brand etc) that can be attached to categories. I.e. the category stroller can have
        a feature called "brand", which can have several options. There are 2 types of features: singulars (things that can be "true" or "false", such as "pet free home") and non-singulars (where users can pick from multiple options, such as gender).
        <br>
        A feature can be assigned to multiple categories.
    </p>
    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'description',
            ['attribute' => 'is_singular', 'class' => \kartik\grid\BooleanColumn::className()],
            ['attribute' => 'is_required', 'class' => \kartik\grid\BooleanColumn::className()],
            [
                'class' => 'yii\grid\ActionColumn',
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
