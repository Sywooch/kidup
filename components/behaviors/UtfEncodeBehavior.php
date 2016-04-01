<?php
namespace app\components\behaviors;

use app\models\BaseActiveRecord;
use yii\base\Behavior;
use app\helpers\Event;
use yii\base\InvalidParamException;
use yii\helpers\HtmlPurifier;

/**
 * Class UtfEncodeBehavior
 * HTMLPurifier behavior.
 *
 * Usage:
 * ```
 * ...
 * 'purifierBehavior' => [
 *     'class' => PurifierBehavior::className(),
 *     'attributes' => [
 *         self::EVENT_BEFORE_VALIDATE => [
 *             'snippet',
 *             'content' => [
 *                 'HTML.AllowedElements' => '',
 *                 'AutoFormat.RemoveEmpty' => true
 *             ]
 *         ]
 *     ],
 *     'textAttributes' => [
 *         self::EVENT_BEFORE_VALIDATE => ['title', 'alias']
 *     ]
 * ]
 * ...
 * ```
 *
 * @property array $attributes Attributes array with settings
 * @property array $textAttributes Text attributes array with settings
 * @property array $purifierOptions Purifier settings
 */
class UtfEncodeBehavior extends Behavior
{
    /**
     * @var array Attributes array
     */
    public $attributes = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        $events = [];
        $events[BaseActiveRecord::EVENT_BEFORE_VALIDATE] = 'encode';
        $events[BaseActiveRecord::EVENT_AFTER_INSERT] = 'decode';
        $events[BaseActiveRecord::EVENT_AFTER_UPDATE] = 'decode';
        $events[BaseActiveRecord::EVENT_AFTER_FIND] = 'decode';
        return $events;
    }

    /**
     * Encode attributes
     *
     * @param Event $event Current event
     */
    public function encode($event)
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->$attribute = utf8_encode($this->owner->$attribute);
        }
    }

    /**
     * Decode attributes
     *
     * @param Event $event Current event
     */
    public function decode($event)
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->$attribute = utf8_decode($this->owner->$attribute);
        }
    }
}