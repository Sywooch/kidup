<?php

namespace api\models\oauth;

use user\models\User;
use Yii;

/**
 * This is the model class for table "oauth_refresh_tokens".
 *
 * @property string $refresh_token
 * @property string $client_id
 * @property integer $user_id
 * @property string $expires
 * @property string $scope
 *
 * @property OauthClient $client
 * @property User $user
 */
class OauthRefreshToken extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oauth_refresh_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['refresh_token', 'client_id', 'expires'], 'required'],
            [['user_id'], 'integer'],
            [['expires'], 'safe'],
            [['refresh_token'], 'string', 'max' => 40],
            [['client_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'refresh_token' => 'Refresh Token',
            'client_id' => 'Client ID',
            'user_id' => 'User ID',
            'expires' => 'Expires',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(OauthClient::className(), ['client_id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Makes a new OauthRefreshToken
     * @param User $user
     * @param OauthClient $client
     * @return OauthRefreshToken
     */
    public static function make(User $user, OauthClient $client)
    {
        $token = new OauthRefreshToken();
        $token->expires = time() + 31*3600*24;
        $token->user_id = $user->id;
        $token->client_id = $client->client_id;
        $token->refresh_token = OauthAccessToken::getRandomToken();
        $token->save();
        return $token;
    }
}