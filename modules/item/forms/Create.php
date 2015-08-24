<?php

namespace app\modules\item\forms;

use app\modules\item\models\Item;
use app\modules\item\models\ItemHasCategory;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "category".
 */
class Create extends Model
{
    public $name;
    public $categories;
    public $item;

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
        ];
    }

    public function formName()
    {
        return 'create-item';
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $item = new Item();
        $item->setScenario('create');
        $item->setAttributes([
            'name' => $this->name,
            'is_available' => 0,
            'owner_id' => \Yii::$app->user->id,
            'condition' => 0,
            'min_renting_days' => 1
        ]);

        if ($item->save()) {
            $this->item = $item;
            foreach ($this->categories as $id => $val) {
                if ($val == 1) {
                    $ihc = new ItemHasCategory();
                    $ihc->item_id = $item->id;
                    $ihc->category_id = $id;
                    $ihc->save();
                }
            }
            return true;
        }

        return false;
    }
}
