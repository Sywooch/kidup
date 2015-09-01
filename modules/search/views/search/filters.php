<!-- query -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?= Yii::t("item", "Search") ?>
        </h6>
    </div>
    <div id="refineQuery" class="panel-collapse collapse in">
        <div class="panel-body" ng-init="searchCtrl.filter.query = searchCtrl.loadParam('query', '<?= $model->query ?>')">
            <input class="form-control" name="query" type="text" ng-model="searchCtrl.filter.query"
                   ng-change="searchCtrl.filterChange()"/>
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
                    'class' => 'form-control location-input',
                    'ng-model' => 'searchCtrl.filter.location',
                    'ng-change' => "searchCtrl.filterChange()",
                    'ng-init' => 'searchCtrl.filter.location = searchCtrl.loadParam("location", "' . $model->location . '")'
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
            <?= Yii::t("item", "Price") ?>
        </h6>
    </div>
    <div id="refinePrice" class="panel-collapse collapse in">
        <div class="panel-body"
             ng-init='
                searchCtrl.filter.priceMin = searchCtrl.params.price[0];
                searchCtrl.filter.priceMax = searchCtrl.params.price[1];
                searchCtrl.filter.priceUnit = searchCtrl.params.priceUnit;
                searchCtrl.updateSlider(searchCtrl.params.price[0], searchCtrl.params.price[1]);
            '>
            <select class="form-control" ng-model="searchCtrl.filter.priceUnit" ng-change="searchCtrl.filterChange()">
                <option value="week">Price per week</option>
                <option value="day">Price per day</option>
                <option value="month">Price per month</option>
            </select>
            <br />
            <div id="price-slider<?php echo $mobile == true ? '-mobile' : '' ?>"></div>
        </div>
        <div class="minPrice">
            {{searchCtrl.filter.priceMin}} DKK
        </div>
        <div class="maxPrice">
            {{searchCtrl.filter.priceMax}} DKK
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
    <div class="panel-body" ng-init='searchCtrl.filter.categories = <?= $model->getCategories('main') ?>; searchCtrl.loadCategories()'>
        <div ng-repeat="category in searchCtrl.filter.categories"
             class="btn btn-default btn-xs smallBottomMargin"
             ng-class="{'btn-primary btn-fill': category.value == 1}"
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
    <div class="panel-body" ng-init='searchCtrl.filter.ages = <?= $model->getCategories('age') ?>; searchCtrl.loadCategories()'>
        <div ng-repeat="age in searchCtrl.filter.ages"
             class="btn btn-default btn-xs smallBottomMargin"
             ng-class="{'btn-primary btn-fill': age.value == 1}"
             ng-click="searchCtrl.selectCategory(age.id)">
            {{age.name}}
        </div>
    </div>
</div>