<?php

/**
 * @var $this \app\extended\web\View
 */
$x = "dataLayer.push(
    'event': 'playVideo',
    'videoId': 'vid-000-123',
    'videoName': 'Skateboarding dog',
    'videoAuthor': 'user-00121',
    'videoFormat': 'hd'
);";
$this->registerJs($x);

$this->registerJs(" dataLayer = [{
            'productSku': 'pbz00123',
            'productName': 'The Original Rider Waite Tarot cards',
            'productPrice': '9.99'
        }];", \app\extended\web\View::POS_BEGIN);
?>

test