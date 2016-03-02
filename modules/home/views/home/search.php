<?php
use app\helpers\ViewHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/**
 * @var \app\extended\web\View $this
 * @var \home\forms\Search $model
 * @var \item\models\Category $defaultCategory
 */

$emptyLocation = \Yii::t('home.search.empty_location', 'Location: Near Me');
$emptySearch = $defaultCategory->getTranslatedName();
$this->registerJsVariables([
    'emptyLocation' => $emptyLocation,
    'emptySearch' => $emptySearch
]);
?>
<div id="search-area" class="hidden-sm visible-md visible-lg" >
    <div class="row search-area" style="margin-top:7px;">
        <div class="container">
            <div class="col-sm-12 col-md-10 col-md-offset-1">
                <div class="row">
                    <?php
                    $form = ActiveForm::begin([
                        'action' => Url::to('search'),
                        'method' => 'get',
                        'id' => 'main-search'
                    ]);
                    ?>
                    <div class="col-sm-9 col-md-6 col-md-offset-2">
                        <input type="text" class="form-control" name="q" id="search-home-query"
                               placeholder="<?= \Yii::t("home.search.placeholder_suggestion", 'e.g. {0}', [
                                   $emptySearch
                               ]) ?>">
                    </div>

                    <div class="col-sm-3 col-md-2">
                        <?= \yii\bootstrap\Html::submitButton(Yii::t("home.search.search_button", "Search"),
                            [
                                'class' => 'btn btn-danger btn-fill btn-wide',
                                'onclick' => ViewHelper::trackClick('home.click_search', null, false)
                            ]) ?>
                    </div>

                    <?php
                    ActiveForm::end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>