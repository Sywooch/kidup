<?php

namespace app\modules\user\forms;

use app\modules\user\models\Location;
use app\modules\user\Module;
use yii\base\Model;

/**
 * SettingsForm gets user's username, email and password and changes them.
 */
class LocationForm extends Model
{
    public $id;
    public $longitude;
    public $latitude;
    public $type;
    public $user_id;
    public $street_name;
    public $street_number;
    public $street_suffix;
    public $zip_code;
    public $city;
    public $country;
    public $created_at;
    public $updated_at;

    /** @var Module */
    protected $module;

    /** @var User */
    private $_user;

    /** @return User */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = \Yii::$app->user->identity;
        }

        return $this->_user;
    }

    /** @inheritdoc */
    public function __construct($config = [])
    {
        $this->module = \Yii::$app->getModule('user');
        $location = Location::find()->where(['user_id' => \Yii::$app->user->id, 'type' => Location::TYPE_MAIN])->one();
        if ($location === null) {
            $location = new Location();
            $location->type = Location::TYPE_MAIN;
        }
        foreach ($location->getAttributes() as $attr => $val) {
            $this->{$attr} = $val;
        }
        $this->setAttributes([
            'email' => $this->user->unconfirmed_email ?: $this->user->email
        ], false);
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['street_name', 'street_number', 'city', 'country'], 'required'],
            [['city'], 'string', 'max' => 100],
            [['zip_code', 'street_suffix'], 'string', 'max' => 50],
            [['street_name'], 'string', 'max' => 256],
            [['street_number'], 'string', 'max' => 10]
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

    /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save()
    {

        if ($this->validate()) {
            //save lovation properly
            $location = Location::find()->where([
                'user_id' => \Yii::$app->user->id,
                'type' => Location::TYPE_MAIN
            ])->one();
            if ($location === null) {
                $location = new Location();
                $location->type = Location::TYPE_MAIN;
                $location->user_id = \Yii::$app->user->id;
            }
            $location->load(\Yii::$app->request->post(), 'location-form');
            return $location->save();
        }
        return false;
    }
}
