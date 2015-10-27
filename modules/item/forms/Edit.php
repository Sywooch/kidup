<?php

namespace item\forms;

use item\models\base\FeatureValue;
use item\models\base\ItemHasFeature;
use item\models\base\ItemHasFeatureSingular;
use \item\models\Category;
use \item\models\Item;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "category".
 */
class Edit extends Model
{
    public $item;
    public $images;
    public $location_id;
    public $name;
    public $price_week;
    public $price_day;
    public $price_month;
    public $price_year;
    public $description;
    public $rules;
    public $min_renting_days;
    public $is_available;
    public $category_id;
    public $photos;
    public $singularFeatures;
    public $features;

    public $categoryData;

    // used for price suggestions
    public $newPrice;

    public function __construct(Item $item)
    {
        $this->item = $item;

        $this->location_id = $item->location_id;
        $this->name = $item->name;
        $this->price_week = $item->price_week;
        $this->price_day = $item->price_day;
        $this->price_month = $item->price_month;
        $this->price_year = $item->price_year;
        $this->description = $item->description;
        $this->min_renting_days = $item->min_renting_days;
        $this->is_available = $item->is_available;
        $this->category_id = $item->category_id;

        $this->images = $item->media;

        foreach ($this->item->itemHasFeatureSingulars as $id) {
            $this->singularFeatures[$id->feature_id] = 1;
        }

        foreach ($this->item->itemHasFeatures as $ihf) {
            $this->features[$ihf->feature_id] = $ihf->feature_value_id;
        }

        $cats = Category::find()->where('parent_id IS NOT NULL')->all();

        foreach ($cats as $cat) {
            $this->categoryData[$cat->id] = $cat->parent->getTranslatedName() . ' - '. $cat->getTranslatedName();
        }

        return parent::__construct();
    }

    public function rules()
    {
        return [
            [['name', 'price_week', 'description', 'location_id', 'category_id'], 'required'],
            [['name', 'description'], 'string'],
            [['price_week', 'price_day', 'price_month', 'location_id', 'category_id'], 'number', 'min' => 1],
            [
                'photos',
                'required',
                'isEmpty' => function () {
                    return count($this->item->itemHasMedia) == 0;
                },
                'message' => \Yii::t('item.edit.should_have_one_photo', 'Please provide atleast one photo of your product')
            ]
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
                'photos',
                'location_id',
                'category_id'
            ],
            'location' => ['location_id'],
            'description' => ['name', 'description'],
            'basics' => ['category_id'],
            'pricing' => ['price_week', 'price_day', 'price_month', 'price_year'],
            'photos' => ['photos'],
        ];
    }

    public function formName()
    {
        return 'edit-item';
    }

    /**
     * Loads and saves the relational features
     */
    public function loadAndSaveFeatures()
    {
        $data = \Yii::$app->request->post();
        if (isset($data[$this->formName()]['singularFeatures'])) {
            $this->singularFeatures = $data[$this->formName()]['singularFeatures'];
            ItemHasFeatureSingular::deleteAll(['item_id' => $this->item->id]);
            foreach ($this->singularFeatures as $id => $val) {
                if ($val == 0) {
                    continue;
                }
                $f = new ItemHasFeatureSingular();
                $f->feature_id = $id;
                $f->item_id = $this->item->id;
                $f->save();
            }
        }
        if (isset($data[$this->formName()]['features'])) {
            $this->features = $data[$this->formName()]['features'];
            ItemHasFeature::deleteAll(['item_id' => $this->item->id]);
            foreach ($this->features as $id => $val) {
                $featureVal = FeatureValue::findOne($val);
                if($featureVal !== null){
                    $f = new ItemHasFeature();
                    $f->feature_id = $id;
                    $f->item_id = $this->item->id;
                    $f->feature_value_id = $featureVal->id;
                    $f->save();
                }else{
                    Yii::warning("Feature ID {$val} not found");
                }
            }
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        
        $item = $this->item;
        $item->location_id = $this->location_id;
        $item->name = $this->name;
        $item->price_week = $this->price_week;
        $item->price_day = $this->price_day;
        $item->price_month = $this->price_month;
        $item->price_year = $this->price_year;
        $item->description = $this->description;
        $item->min_renting_days = $this->min_renting_days;
        $item->is_available = $this->is_available;
        $item->category_id = $this->category_id;

        $item->scenario = 'create';
        if ($item->save()) {
            $this->item = $item;

            return true;
        }
        return false;
    }

    public function isScenarioValid($s)
    {
        $copy = (new \DeepCopy\DeepCopy)->copy($this);
        $copy->setScenario($s);
        return $copy->validate();
    }

    /**
     * Computes how many steps a user still has to make
     * @return int
     */
    public function getStepsToCompleteCount()
    {
        $scenarios = ['location', 'description', 'basics', 'pricing', 'photos'];
        $c = 0;
        foreach ($scenarios as $s) {
            if ($this->isScenarioValid($s) == false) {
                $c++;
            }
        }
        return $c;
    }
}
