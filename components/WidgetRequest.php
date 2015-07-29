<?php
namespace app\components;

use Yii;
use yii\base\Component;

/**
 * Class PartialRequest
 * @package app\components
 * Responsible for generating partial web renders from another module
 */
class WidgetRequest extends Component
{
    /**
     * Constants, (module)_(page)_(type)
     */
    const ITEM_HOME_GRID =      'item.HomeGrid';
    const ITEM_HOME_SEARCH =    'item.HomeSearch';
    const ITEM_MENU_SEARCH =    'item.MenuSearch';

    const BOOKING_CREATE_FORM = 'booking.CreateBooking';

    const USER_PROFILE_IMAGE =  'user.UserImage';
    const USER_LOGIN_MODAL =  'user.Login';
    const USER_REGISTER_MODAL =  'user.Register';

    public static function request($type, $data = null){
        $className =  "app\\modules\\".explode('.', $type)[0]."\\widgets\\".explode('.', $type)[1];
        if(Yii::$app->hasModule(explode('.', $type)[0]) && class_exists($className)){
            Yii::trace('new_instance');
            $instance = new $className($data);
            return $instance->run();
        }else{
            return false;
        }
    }
}