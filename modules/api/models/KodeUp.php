<?php

namespace api\models;


/**
 * This is the base-model class for table "item_facet_value".
 *
 * @property integer $id
 * @property string $device_id
 * @property string $image_url
 * @property int $rating
 */
class KodeUp extends \app\models\BaseActiveRecord
{
    private $kfcServer = 'http://31.187.70.130';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kodeup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'image_url', 'rating'], 'required'],
            [['image_url', 'device_id'], 'string'],
            [['rating'], 'integer', 'max' => 1000],
        ];
    }

//    public function get
}
