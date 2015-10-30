<?php

namespace admin\models;

use Yii;
use yii\web\ServerErrorHttpException;

/**
 * This is the base-model class for table "log".
 *
 * @property integer $id
 * @property string $session
 * @property string $type
 * @property int $created_at
 * @property int $user_id
 * @property string $data
 */
class TrackingEvent extends \yii\db\ActiveRecord
{
    public $types = [
        'pages.click_modal',            // click to open register modal on kidup-pages
        'menu.click_login',             // click login in the menu bar
        'menu.click_register',          // click register in the menu bar
        'menu.switch_to_dk',            // click on danish flag to change language
        'menu.switch_to_en',            // click on english flag to change language
        'menu.click_search',            // click the serch button in the main menu
        'home.click_signup',            // click big call to action signup on homepage
        'home.click_search',            // click big call to action search on homepage
        'home.click_category',          // click on one of the categories on homepage
        'home.click_item',              // click on one of the items on homepage
        'signup_modal.click_facebook',  // clicks facebook in the signup modal
        'item.click_do_like',           // click the do-like-x on the item page
        'item.click_book_now',          // click the request to book button on an item page
        'item.click_short_bookwidget',  // click the short term pricing info in the booking widget of an item page
        'item.click_long_bookwidget',   // click the long term pricing info in the booking widget of an item page
    ];

    public static function tableName()
    {
        return 'tracking_event';
    }

    public function beforeSave($insert){
        if($insert){
            $this->created_at = time();
            $this->user_id = \Yii::$app->user->isGuest ? null : \Yii::$app->user->id;
            $this->session = \Yii::$app->session->hasSessionId ? \Yii::$app->session->id: null;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['session', 'type', 'data', 'country', 'city'], 'string'],
            [['id','created_at','user_id', 'device', 'timestamp'], 'integer'],
            [['session', 'type','data'], 'string', 'max' => 255],
        ];
    }

    public static function track($type, $data = null, $country = null, $city = null, $device = null, $t = null){
        $event = new TrackingEvent([
            'type' => $type,
            'data' => $data,
            'city' => $city,
            'country' => $country,
            'device' => $device,
            'timestamp' => $t,
        ]);
        $event->save();
    }

    public static function checkType($type){
        $types = (new TrackingEvent())->types;
        if(in_array($type, $types)){
            return true;
        }
        return false;
    }
}
