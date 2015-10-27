<?php
use yii\widgets\ListView;

// displaying the search results

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \search\forms\Filter $model
 */
?>
<div class="searchResults" id="results">
    <div class="row">
        <h3 style="margin:0;padding:10px;">
            <?php
            echo $model->resultText;
            ?>
        </h3>

        <?php
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => 'item-view',
            'itemOptions' => ['class' => 'item'],
            'pager' => [
                'class' => \kop\y2sp\ScrollPager::className(),
                'delay' => 200,
                'triggerText' => '',
                'noneLeftText' => ''
            ]
        ]);
        ?>
    </div>
</div>
