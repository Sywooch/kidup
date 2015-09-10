<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "social_account".
 *
 * @property integer $id
 * @property string $provider
 * @property string $client_id
 * @property string $data
 * @property integer $user_id
 */
class SocialAccount extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provider', 'client_id', 'user_id'], 'required'],
            [['data'], 'string'],
            [['user_id'], 'integer'],
            [['provider', 'client_id'], 'string', 'max' => 255],
            [['provider'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'provider' => Yii::t('app', 'Provider'),
            'client_id' => Yii::t('app', 'Client ID'),
            'data' => Yii::t('app', 'Data'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }




}
