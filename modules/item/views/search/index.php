<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = \app\components\ViewHelper::getPageTitle(\Yii::t('title', '{0} KidStuff', [ucfirst($searchModel->query)]));

/**
 * @var \yii\web\View $this
 */
$this->registerJsFile('@itemAssets/js/search.js', [
    'depends' => [
        \yii\web\JqueryAsset::className(),
        \yii\widgets\PjaxAsset::className(),
        \yii\jui\JuiAsset::className()
    ]
]);

echo $this->render('filter_modal.php', [
    'model' => $searchModel,
    'filters' => $filters,
    'searchModel' => $searchModel,
    'categories' => $categories
]);
\app\modules\item\assets\SearchAsset::register($this);
?>

<header id="navbar-sub-menu" class="nav-down">
    <div class="fluid-container">
        <div class="row">
            <div class="pull-left leftPadding">
                <button class="btn btn-neutral btn-sm filter" data-toggle="modal" data-target="#filterModal">
                    <i class="fa fa-filter"></i> <?= Yii::t("item", "Filter") ?>
                </button>
            </div>
            <div class="pull-left leftPadding">
                <div class="itemCount"><?= $dataProvider->totalCount ?>
                    <?= Yii::t("item", "items") ?>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="section" id="search-cards">
    <?php Pjax::begin(); ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-3 hidden-sm hidden-xs">
                <?php $form = ActiveForm::begin([
                    'options' => [
                        'class' => 'form-vertical',
                        'data-pjax' => 0
                    ],
                    'action' => Url::to(['/search']),
                    'method' => 'get'
                ]) ?>
                <div class="card card-refine">
                    <div class="header">
                        <h4 class="title">
                            <?= Yii::t("item", "Filter") ?>
                            <a href="<?= Url::to('@web/search') ?>">
                                <button class="btn btn-danger btn-xs pull-right">
                                    <i class="fa fa-close"></i><?= Yii::t("item", "Clear") ?>
                                </button>
                            </a>
                        </h4>
                    </div>
                    <div class="content">
                        <?php
                        // dynamically load all the filters which are specified inside the controller
                        foreach ($filters as $filter) {
                            echo $this->render('filter/' . $filter['view'] . '.php', [
                                'searchModel' => $searchModel,
                                'form' => $form,
                                'categories' => $categories,
                                'collapsable' => true,
                                'mobile' => false
                            ]);
                        }
                        ?>
                        <!-- end panel -->
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <!-- end card -->
            </div>
            <div class="col-md-9 col-log-10">
                <div class="row col-xs-12">
                    <?php
                    foreach ($filters as $filter) {
                        switch ($filter['view']) {
                            case 'query':
                                if ($searchModel->query != '' && strlen(trim($searchModel->query)) > 0) {
                                    $this->registerJs("hideFilter('query');");
                                    ?>
                                    <div class="btn btn-info btn-xs smallBottomMargin" onclick="window.deleteFilter('query');">
                                        <strong>
                                            <i class="fa fa-close"></i>
                                        </strong>
                                        <?= $searchModel->query ?>
                                    </div>
                                <?php }
                                break;
                            case 'price':
                                if ($searchModel->priceMin != 0 || ($searchModel->priceMax != 999)) {
                                    $this->registerJs("hideFilter('price');");
                                    ?>
                                    <div class="btn btn-info btn-xs smallBottomMargin" onclick="window.deleteFilter('priceMin'); window.deleteFilter('priceMax');">
                                        <strong>
                                            <i class="fa fa-close"></i>
                                        </strong>
                                        <?= 'Price ' . $searchModel->priceMin . ' - ' . $searchModel->priceMax ?>
                                    </div>
                                <?php }
                                break;
                            case 'location':
                                if ($searchModel->location != '' && $searchModel->distance != 3) {
                                    $this->registerJs("hideFilter('location');");
                                    ?>
                                    <div class="btn btn-info btn-xs smallBottomMargin" onclick="window.deleteFilter('location'); window.deleteFilter('distance');">
                                        <strong>
                                            <i class="fa fa-close"></i>
                                        </strong>
                                        <?= $searchModel->location ?>
                                    </div>
                                <?php }
                                break;
                            case 'age':
                                if ($searchModel->age != []) {
                                    $this->registerJs("hideFilter('age');");
                                    ?>
                                    <div class="btn btn-info btn-xs smallBottomMargin" onclick="window.deleteFilter('age');">
                                        <strong>
                                            <i class="fa fa-close"></i>
                                        </strong>
                                        <?= Yii::t("item", "Age") ?>
                                    </div>
                                <?php }
                                break;
                            case 'categories':
                                if ($searchModel->categories != []) {
                                    $this->registerJs("hideFilter('categories');");
                                    ?>
                                    <div class="btn btn-info btn-xs smallBottomMargin" onclick="window.deleteFilter('categories');">
                                        <strong>
                                            <i class="fa fa-close"></i>
                                        </strong>
                                        <?= Yii::t("item", "Categories") ?>
                                    </div>
                                <?php }
                                break;
                        }
                    }
                    ?>
                </div>
                <br/><br/>
                <div  class="row col-xs-12">
                    <?php
                    // render the results
                    echo $this->render('results', [
                        'dataProvider' => $dataProvider
                    ]);
                    ?>
                </div>

            </div>
        </div>
    </div>
    <?php
    $filterViews = [];
    foreach ($filters as $filter) {
        if (isset($filter['subfilter'])) {
            foreach ($filter['subfilter'] as $subfilter) {
                $filterViews[] = $subfilter;
            }
        } else {
            $filterViews[] = $filter['view'];
        }
    }
    $this->registerJs("window.searchQuery = '" . $searchModel->query . "';");
    $this->registerJs("window.filters = ['" . join("', '", $filterViews) . "'];");
    ?>
    <?php Pjax::end(); ?>
</section>

