<?php

namespace app\modules\item\forms;

use app\modules\item\models\Category;
use app\modules\item\models\Item;
use app\modules\item\models\ItemHasCategory;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 */
class Edit extends Model
{
    public $item;
    public $categories;
    public $images;

    public $location_id;
    public $name;
    public $price_week;
    public $price_day;
    public $price_month;
    public $description;
    public $rules;
    public $min_renting_days;
    public $is_available;
    public $condition;
    public $photos;

    // used for price suggestions
    public $newPrice;

    public function __construct(Item $item)
    {
        $this->item = $item;
        $this->name = $item->name;
        $this->price_week = $item->price_week;
        $this->price_day = $item->price_day;
        $this->price_month = $item->price_month;
        $this->description = $item->description;
        $this->min_renting_days = $item->min_renting_days;
        $this->is_available = $item->is_available;
        $this->location_id = $item->location_id;
        $this->condition = $item->condition;
        $this->categories = [
            Category::TYPE_MAIN => $item->categoriesMain,
            Category::TYPE_AGE => $item->categoriesAge,
            Category::TYPE_SPECIAL => $item->categoriesSpecial
        ];
        foreach ($this->categories as $type => $c) {
            foreach ($c as $id => $a) {
                $this->categories[$type][$id] = 1;
            }
        }

        $this->images = $item->media;
        return parent::__construct();
    }

    public function rules()
    {
        return [
            [['name', 'price_week', 'description', 'location_id', 'photos', 'condition'], 'required'],
            [['name', 'description'], 'string'],
            [['price_week', 'price_day', 'price_month', 'location_id'], 'number', 'min' => 1],
            ['photos', function($attr, $params){
                if(count($this->item->itemHasMedia) == 0){
                    $this->addError('photos', \Yii::t('item', 'Atleast one photo should be set'));
                }
            }]
        ];
    }

    public function scenarios()
    {
        return [
            'default' => [
                'name',
                'price_week',
                'min_renting_days',
                'price_day',
                'price_month',
                'description',
                'condition',
                'photos',
                'location_id'
            ],
            'location' => ['location_id'],
            'description' => ['name', 'description'],
            'basics' => ['condition'],
            'pricing' => ['price_week', 'price_day', 'price_month'],
            'photos' => ['photos'],
        ];
    }

    public function formName()
    {
        return 'edit-item';
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $item = $this->item;
        $item->name = $this->name;
        $item->description = $this->description;
        $item->min_renting_days = $this->min_renting_days;
        $item->price_week = $this->price_week;
        $item->price_day = $this->price_day;
        $item->price_month = $this->price_month;
        $item->condition = $this->condition;
        $item->location_id = $this->location_id;
        $item->scenario = 'create';
        if ($item->save()) {
            $this->item = $item;
            ItemHasCategory::deleteAll(['item_id' => $item->id]);
            foreach ($this->categories as $cat) {
                foreach ($cat as $id => $val) {
                    if ($val == 1) {
                        $ihc = new ItemHasCategory();
                        $ihc->item_id = $item->id;
                        $ihc->category_id = $id;
                        $ihc->save();
                    }
                }
            }
        }
        return $item;
    }

    public function isScenarioValid($s){
        $copy = (new \DeepCopy\DeepCopy)->copy($this);
        $copy->setScenario($s);
        return $copy->validate();
    }

    /**
     * Computes how many steps a user still has to make
     * @return int
     */
    public function getStepsToCompleteCount(){
        $scenarios = ['location','description','basics', 'pricing','photos'];
        $c = 0;
        foreach ($scenarios as $s) {
            if($this->isScenarioValid($s) == false)$c++;
        }
        return $c;
    }
}
