<?php

namespace item\forms;

use item\models\itemFacetValue\ItemFacetValue;
use item\models\itemHasItemFacet\ItemHasItemFacet;
use item\models\category\Category;
use item\models\item\Item;
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
    public $is_available;
    public $category_id;
    public $photos;
    public $singularItemFacets;
    public $item_facets;

    public $categoryData;

    // used for price suggestions
    public $newPrice;

    public function __construct(Item $item)
    {
        $this->item = $this->loadItem();

        $this->images = $item->media;

        foreach ($this->item->itemHasItemFacets as $ihf) {
            $this->item_facets[$ihf->item_facet_id] = $ihf->item_facet_value_id;
        }

        $cats = Category::find()->where('parent_id IS NOT NULL')->all();

        foreach ($cats as $cat) {
            $this->categoryData[$cat->id] = $cat->parent->getTranslatedName() . ' - ' . $cat->getTranslatedName();
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
                'message' => \Yii::t('item.edit.should_have_one_photo',
                    'Please provide atleast one photo of your product')
            ]
        ];
    }

    public function scenarios()
    {
        return [
            'default' => [
                'name',
                'price_week',
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
     * Loads and saves the relational item_facets
     */
    public function loadAndSaveFacets()
    {
        $data = \Yii::$app->request->post();
        if (isset($data[$this->formName()]['item_facets'])) {
            $this->item_facets = $data[$this->formName()]['item_facets'];
            ItemHasItemFacet::deleteAll(['item_id' => $this->item->id]);
            foreach ($this->item_facets as $id => $val) {
                $itemFacetValue = ItemFacetValue::find()->where(['id' => $val])->one();
                if ($itemFacetValue !== null) {
                    $f = new ItemHasItemFacet();
                    $f->item_facet_id = $id;
                    $f->item_id = $this->item->id;
                    $f->item_facet_value_id = $itemFacetValue->id;
                    $f->save();
                } else {
                    Yii::warning("ItemFacet ID {$val} not found");
                }
            }
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $item = $this->loadItem();

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

    private function loadItem()
    {
        $item = $this->item;
        $item->location_id = $this->location_id;
        $item->name = $this->name;
        $item->price_week = $this->price_week;
        $item->price_day = $this->price_day;
        $item->price_month = $this->price_month;
        $item->price_year = $this->price_year;
        $item->description = $this->description;
        $item->is_available = $this->is_available;
        $item->category_id = $this->category_id;
        return $item;
    }
}
