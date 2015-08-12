$(document).ready(function () {
    $('.step-item').on("mouseover", function () {
        $('.step-item').removeClass('active');
        $(this).fadeIn().addClass('active');
        var selected = $(this).attr("id");
        var lastChar = selected[selected.length - 1];
        if (lastChar == 1) {
            $("div[class*='step-d']").removeClass('active');
            $('.step-d-1').addClass('active');
        } else if (lastChar == 2) {
            $("div[class*='step-d']").removeClass('active');
            $('.step-d-2').addClass('active');
        }
        else if (lastChar == 3) {
            $("div[class*='step-d']").removeClass('active');
            $('.step-d-3').addClass('active');
        }
        else if (lastChar == 4) {
            $("div[class*='step-d']").removeClass('active');
            $('.step-d-4').addClass('active');
        }
    });

    $("#owl-kidup").owlCarousel({
        navigation: true,
        navigationText: [
            "<i class='fa fa-chevron-left'></i>",
            "<i class='fa fa-chevron-right'></i>"
        ],
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true,
        loop: true,
        items: 1,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: false
    });

    var typist = document.querySelector("#typist-element");
    new Typist(typist, {
        letterInterval: 60,
        textInterval: 3000,
        onSlide: function(text, options) {
            options.typist.getAttribute("data-typist-suffix");
            return false;
        }
    });
});

