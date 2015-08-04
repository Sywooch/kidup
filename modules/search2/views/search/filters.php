<!-- query -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title" style="padding: 17px 15px;">
            <?= Yii::t("item", "Search") ?>
        </h6>
    </div>
    <div id="refineQuery" class="panel-collapse collapse in">
        <div class="panel-body">
            <input class="form-control" type="text" ng-model="searchCtrl.filter.query" ng-change="searchCtrl.filterChange()"/>
        </div>
    </div>
</div>

<!--location-->

<div class="panel-group" id="accordion">
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
                        'ng-change' => "searchCtrl.filterChange()"
                    ],
                    'autocompleteOptions' => [
                        'types' => ['geocode']
                    ],
                    'name' => 'autoCompleteLocation'
                ]); ?>
                <br>

                <div id="distance-slider" ng-model="searchCtrl.filter.locationDistance" ng-change="searchCtrl.filterChange()"></div>
            </div>
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
    </div>
</div>

<!-- categories -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?= Yii::t("item", "Categories") ?>
        </h6>
    </div>
    <div class="panel-body ">
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
    <div class="panel-body">
        <div ng-repeat="age in searchCtrl.filter.ages"
             class="btn btn-default btn-xs smallBottomMargin"
             ng-class="{'btn-primary': age.value == 1}"
             ng-click="searchCtrl.selectCategory(age.id)">
            {{age.name}}
        </div>
    </div>
</div>