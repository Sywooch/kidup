<?php

namespace user\models\socialAccount;

use Yii;

/**
 * This is the base-model class for table "social_account".
 *
 * @property integer $id
 * @property string $provider
 * @property string $client_id
 * @property string $data
 * @property integer $user_id
 *
 * @property \user\models\user\User $user
 */
class SocialAccountBase extends \app\components\models\BaseActiveRecord
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
            [
                ['provider', 'client_id'],
                'unique',
                'targetAttribute' => ['provider', 'client_id'],
                'message' => \Yii::t('social_account.error_already_used_combination',
                    'The combination of Provider and Client ID has already been taken.')
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'provider' => 'Provider',
            'client_id' => 'Client ID',
            'data' => 'Data',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\user\models\user\User::className(), ['id' => 'user_id']);
    }
}
