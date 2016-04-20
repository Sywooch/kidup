<?php

/**
 * @var $this \app\components\view\View
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
        }];", \app\components\view\View::POS_BEGIN);
?>

test