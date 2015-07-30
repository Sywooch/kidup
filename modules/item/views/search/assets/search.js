window.mobileFilter = false;
window.filterData = {};
window.deltaFilterData = [];
window.currentFilter = null;
window.appliedFilters = [];
window.filters = [];

function filterDataToQuery(filters, filterData) {
    var query = '';
    $.each(filters, function(filterIndex) {
        var filter = filters[filterIndex];
        if (typeof(filterData[filter]) == typeof({})) {
            var value = filterData[filter]['value'];
            if (filterData[filter]['type'] === 'set') {
                var subquery = '';
                var values = filterData[filter]['value'];
                for (var i = 0; i < values.length; i++) {
                    subquery += values[i] + ',';
                }
                subquery = subquery.substr(0, subquery.length - 1);
                value = subquery;
            }
        } else {
            var value = '';
        }
        query += filter + '|' + value + '|';
    });
    return query;
}

// needs to be wrapped like this for the pjax to work
$(document).on('ready pjax:success', function() {
    $(".category-clickable-button").click(function () {
        $(this).toggleClass('btn-primary btn-fill');
        // select the next element, which is the form
        var hiddenEl = $(this).next();
        var currentVal = hiddenEl.val();
        alert(currentVal);
        var val = currentVal == 0 ? 1 : 0;
        alert(val);
        hiddenEl.val(val);

        var dataID = $(this).attr('data-id');
        window.changeDetected(true, $(this).attr('filter'), $(this).attr('domain'),
            {
                index: dataID,
                value: val
            });
    });

// select all selected categories
    $("[id^=categories-]").each(function () {
        // get the id
        var myRegexp = /(\d+)/g; // Numbers dash Numbers
        var id = myRegexp.exec(this.id)[0];
        if (this.value == 1) {
            $("[data-id=" + id + "]").toggleClass('btn-primary btn-fill');
        }
    });
    var isLoading = false;

    window.changeDetected = function (direct, variable, type, value) {
        if (!window.mobileFilter) {
            setFilter(variable, type, value);
            if (!isLoading) {
                isLoading = true;
                setTimeout(function () {
                    applyFilter();
                }, 500);
            }
        } else {
            window.deltaFilterData.push({
                variable: variable,
                type: type,
                value: value
            });
        }
    };

    window.deleteFilter = function(filter) {
        $('.btnSelectFilter-' + filter).show();
        setFilter(filter, 'string', '');
        applyFilter();
    };

    $(':input').change(function(direct) {
        var elm = $(this);
        window.changeDetected(direct, elm.attr('filter'), elm.attr('domain'), elm.val());
        for (var i = 1; i <= 5; i++) {
            setTimeout(function () {
                window.changeDetected(direct, elm.attr('filter'), elm.attr('domain'), elm.val());
            }, i * 500);
        }
    });

    $(':input').on('keydown', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            window.changeDetected(false, $(this).attr('filter'), $(this).attr('domain'), $(this).val());
        }
    });

    $('.menubar-search-query').val(window.searchQuery);
});

function setFilter(variable, type, value) {
    var defaultType = null;
    if (type === 'set') {
        defaultType = [];
    }
    if (typeof(window.filterData[variable]) !== typeof({})) {
        window.filterData[variable] = {
            'type': type,
            'value': defaultType
        }
    }

    if (type !== 'set') {
        // just overwrite the value
        window.filterData[variable]['value'] = value;
    } else {
        var filterValue = window.filterData[variable]['value'];
        for (var i = 0; i < filterValue.length; i++) {
            window.filterData[variable]['value'][i] = "" + filterValue[i];
        }
        var index = filterValue.indexOf("" + value.index);
        if (value.value === 0) {
            // remove the value
            while (index !== -1) {
                window.filterData[variable]['value'].splice(index, 1);
                var index = filterValue.indexOf("" + value.index);
            }
        } else {
            // add the value
            if (index === -1) {
                window.filterData[variable]['value'].push(value.index);
            }
        }
    }
}

function applyFilter() {
    for (var i = 0; i < window.deltaFilterData.length; i++) {
        var filter = window.deltaFilterData[i];
        setFilter(filter['variable'], filter['type'], filter['value']);
    }
    window.deltaFilterData = [];
    window.appliedFilters.push(window.currentFilter);
    $.pjax({
        timeout: 20000,
        url: 'search?q=' + filterDataToQuery(window.filters, window.filterData),
        fragment: '#searchResults',
        container: '#searchResults',
        scrollTo: false,
        push: true,
        replace: false
    });
    $('.btnSelectFilter-' + window.currentFilter).hide();
    restoreFilterModal();
    $('#filterModal').modal('hide');
}

function hideFilter(filter) {
    $('.btnSelectFilter-' + filter).hide();
}

function updateDistanceValue(val) {
    if(val == 0) $(".distanceValue").text("next to you");
    if (val <= 1) {
        return $(".distanceValue").text( val * 1000 + " m");
    }
    if (val <= 2) {
        return $(".distanceValue").text( Math.round((val - 1)*10) + " km");
    }
    if (val < 3) {
        return  $(".distanceValue").text( Math.round((val - 2)*100) + " km");
    }
    if (val == 3){
        return $(".distanceValue").text("all");
    }
}

function showFilter(view) {
    window.mobileFilter = true;
    window.deltaFilterData = [];
    window.currentFilter = view;
    $('#filterModal .filterSelect').hide();
    $('#filterModal .filter-' + view).removeClass('hidden');
    $('#filterModal .btnBack').removeClass('hidden');
    $('#filterModal .btnApply').removeClass('hidden');
}

function restoreFilterModal() {
    window.mobileFilter = false;
    window.deltaFilterData = [];
    $('#filterModal .btnBack').addClass('hidden');
    $('#filterModal .filterSelect').show();
    $('#filterModal .filter').addClass('hidden');
    $('#filterModal .filter :input').val('');
    $('#filterModal .btnApply').addClass('hidden');
}