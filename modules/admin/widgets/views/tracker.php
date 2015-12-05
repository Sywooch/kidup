<script>
    (function () {
        window.kidupTracker = function (type, data) {
            if (type == 'page_view' && document.referrer.indexOf(location.protocol + "//" + location.host) !== 0) {
                setTimeout(function () {
                    window.kidupTracker('init', {});
                }, 10);

            }

            setTimeout(function () {
                var xmlhttp;

                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                var l = window.navigator.userLanguage || window.navigator.language;
                var m = /Mobi/.test(navigator.userAgent);

                if (type == 'init') {
                    data.p = window.navigator.platform;

                    data.height = window.screen.height;
                    data.width = window.screen.width;
                    data.availHeight = window.screen.availHeight;
                    data.availWidth = window.screen.availWidth;
                    data.ref = document.referrer;
                    data.ua = window.navigator.userAgent;
                    data.os = (function () {
                        var a = window.navigator.userAgent;
                        if (/Windows/i.test(a)) {
                            if (/Phone/.test(a) || /WPDesktop/.test(a)) {
                                return 'Windows Phone';
                            }
                            return 'Windows';
                        } else if (/(iPhone|iPad|iPod)/.test(a)) {
                            return 'iOS';
                        } else if (/Android/.test(a)) {
                            return 'Android';
                        } else if (/(BlackBerry|PlayBook|BB10)/i.test(a)) {
                            return 'BlackBerry';
                        } else if (/Mac/i.test(a)) {
                            return 'Mac OS X';
                        } else if (/Linux/.test(a)) {
                            return 'Linux';
                        } else {
                            return '';
                        }
                    })();
                    data.ref_domain = (function () {
                        var split = document.referrer.split("/");
                        if (split.length >= 3) {
                            return split[2];
                        }
                        return "";
                    })();
                    data.device = (function () {
                        if (/Windows Phone/i.test(window.navigator.userAgent) || /WPDesktop/.test(window.navigator.userAgent)) {
                            return 'Windows Phone';
                        } else if (/iPad/.test(window.navigator.userAgent)) {
                            return 'iPad';
                        } else if (/iPod/.test(window.navigator.userAgent)) {
                            return 'iPod Touch';
                        } else if (/iPhone/.test(window.navigator.userAgent)) {
                            return 'iPhone';
                        } else if (/(BlackBerry|PlayBook|BB10)/i.test(window.navigator.userAgent)) {
                            return 'BlackBerry';
                        } else if (/Android/.test(window.navigator.userAgent)) {
                            return 'Android';
                        } else {
                            return '';
                        }
                    })();
                }
                if(typeof data === "object"){
                    data = JSON.stringify(data);
                }

                xmlhttp.open("get", "<?= \yii\helpers\Url::to('@web/api/event', true) ?>?type=" + type + "&data=" + data + "&t=" + new Date().getTime() + "&s=1&l=" + l + "&m=" + m, true);
                xmlhttp.send();
            }, 10);


        }
    })();

    window.scrollTop = function(){
        return (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
    }

    setTimeout(function () {
        var scrollTop = window.scrollTop();
        window.kidupTracker('ping', {focus: window.isInFocus, t: 5, scroll: scrollTop});
    }, 5 * 1000);
    setTimeout(function () {
        var scrollTop = window.scrollTop();
        window.kidupTracker('ping', {focus: window.isInFocus, t: 15, scroll: scrollTop});
    }, 15 * 1000);
    setTimeout(function () {
        var scrollTop = window.scrollTop();
        window.kidupTracker('ping', {focus: window.isInFocus, t: 30, scroll: scrollTop});
    }, 30 * 1000);
    setTimeout(function () {
        var scrollTop = window.scrollTop();
        window.kidupTracker('ping', {focus: window.isInFocus, t: 60, scroll: scrollTop});
    }, 60 * 1000);
    setTimeout(function () {
        var scrollTop = window.scrollTop();
        window.kidupTracker('ping', {focus: window.isInFocus, t: 120, scroll: scrollTop});
    }, 120 * 1000);
    setTimeout(function () {
        var scrollTop = window.scrollTop();
        window.kidupTracker('ping', {focus: window.isInFocus, t: 600, scroll: scrollTop});
    }, 600 * 1000);
    window.isInFocus = true;
    window.onfocus = function () {
        window.isInFocus = true;
    };
    window.onblur = function () {
        window.isInFocus = false;
    };
</script>