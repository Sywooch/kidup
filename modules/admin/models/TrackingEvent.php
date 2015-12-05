<?php

namespace admin\models;

use Yii;

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
        'pages.click_modal',
        // click to open register modal on kidup-pages
        'menu.click_login',
        // click login in the menu bar
        'menu.click_register',
        // click register in the menu bar
        'menu.switch_to_dk',
        // click on danish flag to change language
        'menu.switch_to_en',
        // click on english flag to change language
        'menu.click_search',
        // click the serch button in the main menu
        'home.click_signup',
        // click big call to action signup on homepage
        'home.click_search',
        // click big call to action search on homepage
        'home.click_category',
        // click on one of the categories on homepage
        'home.click_item',
        // click on one of the items on homepage
        'signup_modal.click_facebook',
        // click facebook in the signup modal
        'item.click_do_like',
        // click the do-like-x on the item page
        'item.click_book_now',
        // click the request to book button on an item page
        'item.click_short_bookwidget',
        // click the short term pricing info in the booking widget of an item page
        'item.click_long_bookwidget',
        // click the long term pricing info in the booking widget of an item page
        'init',
        // provides initial information on a session, such as user agent, screen size etc.
        'ping',
        // a page ping - a user that is still on a page. Done in intervals, with some info (scroll, second of ping, if page is in focus)
    ];

    public static function tableName()
    {
        return 'tracking_event';
    }

    public static function track(
        $type,
        $data = null,
        $country = null,
        $city = null,
        $is_mobile = null,
        $t = null,
        $language = null,
        $source = null,
        $ip = null
    ) {
        $event = new TrackingEvent([
            'type' => $type,
            'data' => $data,
            'city' => $city,
            'country' => $country,
            'is_mobile' => $is_mobile,
            'timestamp' => $t,
            'language' => $language,
            'source' => $source,
            'ip' => $ip
        ]);
        $event->save();
    }

    public static function checkType($type)
    {
        $types = (new TrackingEvent())->types;
        if (in_array($type, $types)) {
            return true;
        }
        return false;
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_at = time();
            $this->user_id = \Yii::$app->user->isGuest ? null : \Yii::$app->user->id;
            $this->session = \Yii::$app->session->hasSessionId ? \Yii::$app->session->id : null;
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
            [['session', 'type', 'data', 'country', 'city', 'language', 'ip'], 'string'],
            [['id', 'created_at', 'user_id', 'timestamp', 'source'], 'integer'],
            [['session', 'type', 'ip'], 'string', 'max' => 255],
        ];
    }
}
