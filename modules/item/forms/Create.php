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
    public $categories;
    public $item;
    public $first_name;
    public $last_name;
    public $profile_image;

    public function rules()
    {
        return [
            [
                'first_name',
                function () {
                    if (!\Yii::$app->user->identity->profile->validate('first_name')) {
                        foreach(\Yii::$app->user->identity->profile->getErrors('first_name') as $e){
                            $this->addError('first_name', $e);
                        }
                    }
                    return true;
                }
            ],
            [
                'last_name',
                function () {
                    if (!\Yii::$app->user->identity->profile->validate('last_name')) {
                        $this->addError('last_name', \Yii::$app->user->identity->profile->validate('last_name'));
                    }
                }
            ]
        ];
    }

    public function formName()
    {
        return 'create-item';
    }

    public function save()
    {

        /**
         * @var \app\modules\user\models\Profile $profile
         */
        $profile = \Yii::$app->user->identity->profile;

        if (isset($this->first_name)) {
            $profile->first_name = $this->first_name;
        }
        if (isset($this->last_name)) {
            $profile->last_name = $this->last_name;
        }
        if (isset($this->profile_image)) {
            $profile->img = $this->profile_image;
        }

        if (!$this->validate()) {
            return false;
        }

        $profile->save();

        $item = new Item();
        $item->setScenario('create');
        $item->setAttributes([
            'is_available' => 0,
            'owner_id' => \Yii::$app->user->id,
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
