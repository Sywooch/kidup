// this fixes the jittery bug of the parallax on ie, see
// https://stackoverflow.com/questions/27966735/why-background-image-is-moving-when-scroll-at-ie/28529089#28529089

$('body').on("mousewheel", function () {
    event.preventDefault();

    var wheelDelta = event.wheelDelta;

    var currentScrollPosition = window.pageYOffset;
    window.scrollTo(0, currentScrollPosition - wheelDelta);
});