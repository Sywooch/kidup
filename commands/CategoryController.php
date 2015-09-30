<?php

namespace app\commands;

use search\models\ItemSearch;
use Yii;
use yii\console\Controller;

class CategoryController extends Controller
{

    public function actionFeatures()
    {

    }

    public function actionGenerate(){
        ItemSearch::updateSearch();
    }
}

