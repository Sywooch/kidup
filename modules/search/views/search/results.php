<?php
use app\modules\item\widgets\ItemCard;

// displaying the search results

/**
 * @var array $results
 * @var \app\modules\search\forms\Filter $model
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
        if ($model->resultsAreFake) {
            ?><p><?php
            $cat = \app\modules\item\models\Category::findOne(['id' => $model->categories[0]]);
            if($cat !== null){
                echo \Yii::t('search.results_interesting_products_text', 'Below are some products you might find interesting in {0}.', [
                    '<b>' . $cat->getTranslatedName() . '</b>'
                ]);
            }
            ?></p><?php
        }
        foreach ($results as $result) {
            echo ItemCard::widget([
                'model' => $result,
                'showDistance' => true,
                'numberOfCards' => 3,
                'titleCutoff' => 32
            ]);
        }
        $pagination = new \yii\data\Pagination([
            'totalCount' => $model->estimatedResultCount,
            'pageSize' => 12,
            'pageParam' => 'search-filter[page]'
        ]);
        $pagination->setPage($model->page - 1);
        echo \yii\widgets\LinkPager::widget([
            'pagination' => $pagination,
        ]);
        ?>
    </div>
</div>
