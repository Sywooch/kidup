<script>
    (function () {
        window.kidupTracker = function (type, data) {
            setTimeout(function(){
                var xmlhttp;

                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.open("get", "<?= \yii\helpers\Url::to('@web/api/event', true) ?>?type=" + type + "&data=" + data+"&t="+new Date().getTime(), true);
                xmlhttp.send();
            },10);
        }
    })();
</script>