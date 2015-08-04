<!-- query -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?= Yii::t("item", "Search") ?>
        </h6>
    </div>
    <div id="refineQuery" class="panel-collapse collapse in">
        <div class="panel-body" ng-init='searchCtrl.filter.query = "<?= $model->query ?>"'>
            <input class="form-control" type="text" ng-model="searchCtrl.filter.query" ng-change="searchCtrl.filterChange()"/>
        </div>
    </div>
</div>

<!--location-->
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?= Yii::t("item", "Location") ?>
        </h6>
    </div>
    <div id="refine-location" class="panel-collapse collapse in">
        <div class="panel-body">
            <?= \app\modules\item\widgets\GoogleAutoComplete::widget([
                'options' => [
                    'class' => 'form-control',
                    'ng-model' => 'searchCtrl.filter.location',
                    'ng-change' => "searchCtrl.filterChange()",
                    'ng-init' => 'searchCtrl.filter.location = "' . $model->location . '"'
                ],
                'autocompleteOptions' => [
                    'types' => ['geocode']
                ],
                'name' => 'autoCompleteLocation'
            ]); ?>
        </div>
    </div>
</div>

<!-- price -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?= Yii::t("item", "Price (Weekly)") ?>
        </h6>
    </div>
    <div id="refinePrice" class="panel-collapse collapse in">
        <div class="panel-body">
            <div id="price-slider" ng-model="searchCtrl.filter.prices"></div>
        </div>
        <div class="minPrice">
            {{searchCtrl.minPrice}}
        </div>
        <div class="maxPrice">
            {{searchCtrl.maxPrice}}
        </div>
    </div>
</div>

<!-- categories -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?= Yii::t("item", "Categories") ?>
        </h6>
    </div>
    <div class="panel-body" ng-init='searchCtrl.filter.categories = <?= $model->getCategories('main') ?>'>
        <div ng-repeat="category in searchCtrl.filter.categories"
             class="btn btn-default btn-xs smallBottomMargin"
             ng-class="{'btn-primary': category.value == 1}"
             ng-click="searchCtrl.selectCategory(category.id)">
            {{category.name}}
        </div>
    </div>
</div>

<!-- ages -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?= Yii::t("item", "Age") ?>
        </h6>
    </div>
    <div class="panel-body" ng-init='searchCtrl.filter.ages = <?= $model->getCategories('age') ?>'>
        <div ng-repeat="age in searchCtrl.filter.ages"
             class="btn btn-default btn-xs smallBottomMargin"
             ng-class="{'btn-primary': age.value == 1}"
             ng-click="searchCtrl.selectCategory(age.id)">
            {{age.name}}
        </div>
    </div>
</div>