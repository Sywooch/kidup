<?php

namespace item\models;

use app\components\Cache;
use app\models\BaseQuery;
use Carbon\Carbon;
use images\components\ImageHelper;
use search\components\ItemSearchDb;
use user\models\base\Currency;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This is the model class for table "item".
 */
class ItemQuery extends BaseQuery
{
    public function available($state = 1)
    {
        if($state == false){
            $state = 0;
        }
        return $this->andWhere(['is_available' => $state]);
    }
}
