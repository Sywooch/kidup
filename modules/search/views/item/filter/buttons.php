<!-- Filter buttons -->
<div class="row bottomMargin">
    <div class="col-sm-12 col-xs-12 filterButtons">
        <!-- @todo Buttons should be initialized via JavaScript for more efficiency and code readability -->

        <!-- Query button -->
        <button ng-show="query.length > 0" class="btn btn-xs" ng-click="query = ''">
            <i class="fa fa-close"></i> Search term: {{query}}
        </button>
    </div>
</div>