<?php

namespace app\modules\item\forms;

use app\models\base\Category;
use app\modules\item\models\Item;
use app\modules\item\models\ItemHasCategory;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "category".
 */
class Create extends Model
{
    public $category;
    public $item;
    public $first_name;
    public $last_name;
    public $categoryData = [];

    public function rules()
    {
        return [
            [
                'first_name',
                'required',
                'when' =>
                    function () {
                        return \Yii::$app->user->identity->profile->hasErrors('first_name');
                    }
            ],
            [
                'last_name',
                'required',
                'when' =>
                    function () {
                        return \Yii::$app->user->identity->profile->hasErrors('last_name');
                    }
            ],
            ['category', 'required'],
            ['category', 'integer'],

        ];
    }

    public function init()
    {
        parent::init();

        $cats = Category::find()->where('parent_id IS NOT NULL')->all();

        foreach ($cats as $cat) {
            $this->categoryData[Yii::t('categories_and_features', $cat->parent->name)][$cat->id] = Yii::t('categories_and_features',$cat->name);
        }
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

        if (!$this->validate()) {
            return false;
        }

        if ($profile->isAttributeChanged('first_name') || $profile->isAttributeChanged('last_name')) {
            $profile->save();
        }

        $c = Category::find()->where(['id' => $this->category])->count();
        if($c == null){
            $this->addError('category', \Yii::t('item', 'Category is not valid'));
            return false;
        }

        $item = new Item();
        $item->setScenario('create');
        $item->setAttributes([
            'is_available' => 0,
            'owner_id' => \Yii::$app->user->id,
            'min_renting_days' => 1,
            'category_id' => (int) $this->category
        ]);
        
        if ($item->save()) {
            $this->item = $item;

            return true;
        }

        return false;
    }
}
