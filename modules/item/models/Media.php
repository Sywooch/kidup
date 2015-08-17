<?php

namespace app\modules\item\models;

use Carbon\Carbon;
use Yii;

/**
 * This is the model class for table "media".
 */
class Media extends \app\models\base\Media
{
    const TYPE_IMG = 'image';
    const LOC_LOCAL= 'local';
    
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
        }
        $this->updated_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
        return parent::beforeValidate();
    }
}
