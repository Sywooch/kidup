<?php

namespace app\modules\item\forms;

use app\modules\item\models\Item;
use app\modules\item\models\Location;
use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "category".
 */
class LocationForm extends Model
{
    public $id;
    public $street;
    public $street_suffix;
    public $street_number;
    public $zip_code;
    public $city;
    public $country;
    public $item_id;
    private $item;

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['street', 'city', 'country', 'zip_code', 'item_id'], 'required'],
            [['city'], 'string', 'max' => 100],
            [['country'], 'string', 'max' => 100],
            [['zip_code'], 'string', 'max' => 10],
            [['street'], 'string', 'max' => 256],
            [['street_suffix'], 'string', 'max' => 100],
            [['item_id'], 'integer'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        $l = new Location();
        return $l->attributeLabels();
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'location-form';
    }

    public function loadItem(){
        /**
         * @var Item $item
         */
        $this->item = Item::find()->where(['id' => $this->item_id])->one();
        if ($this->item == null) {
            throw new NotFoundHttpException('Item does not exist');
        }
        if (!$this->item->isOwner()) {
            throw new ForbiddenHttpException();
        }
        return true;
    }

    /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $location = new Location();
            $location->type = Location::TYPE_ITEM;
            $location->user_id = \Yii::$app->user->id;
            $location->load(\Yii::$app->request->post(), 'location-form');
            $location->setStreetNameAndNumber($this->street);
            if($location->save()){
                $this->item->location_id = $location->id;
            }
        }

        return false;
    }

}
