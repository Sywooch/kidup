$(document).ready(function() {
    // initialize the mobile menu search
    initializeSearchWidget($("#search-filter-query"), $('#search-filter-location'), $('#menu-search-submit-button'), true);

    // initialize the desktop menu search
    initializeSearchWidget($("#menu-search-autocomplete"), null, $('#menu-search-form .btn'), true);
});