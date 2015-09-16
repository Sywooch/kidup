<?php

namespace app\commands;

use app\models\base\Category;
use app\models\base\CategoryHasFeature;
use app\models\base\Feature;
use app\models\base\FeatureValue;
use app\modules\item\models\Item;
use app\modules\search\models\ItemSearch;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class CategoryController extends Controller
{

    public function actionFeatures()
    {

    }

    public function actionGenerate(){
        ItemSearch::updateSearch();
    }
}

