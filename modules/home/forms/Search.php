<?php

namespace app\modules\home\forms;

use app\modules\item\models\Category;
use app\modules\item\models\Item;
use app\modules\item\models\ItemHasCategory;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 */
class Search extends Model{
    public $query;

    public function __construct()
    {
        return parent::__construct();
    }

    public function rules()
    {
        return [
        ];
    }


    public function formName()
    {
        return 'search-home';
    }
}
