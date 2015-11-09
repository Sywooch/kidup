<?php

namespace api\models\oauth;

use Yii;

/**
 * This is the model class for table "oauth_clients".
 *
 * @property string $client_id
 * @property string $client_secret
 * @property string $redirect_uri
 * @property string $grant_types
 * @property string $scope
 * @property integer $user_id
 *
 * @property OauthAccessToken[] $oauthAccessTokens
 * @property OauthRefreshToken[] $oauthRefreshTokens
 */
class OauthClient extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oauth_client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'client_secret', 'redirect_uri', 'grant_types'], 'required'],
            [['user_id'], 'integer'],
            [['client_id', 'client_secret'], 'string', 'max' => 32],
            [['redirect_uri'], 'string', 'max' => 1000],
            [['grant_types'], 'string', 'max' => 100],
            [['scope'], 'string', 'max' => 2000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'client_id' => 'Client ID',
            'client_secret' => 'Client Secret',
            'redirect_uri' => 'Redirect Uri',
            'grant_types' => 'Grant Types',
            'scope' => 'Scope',
            'user_id' => 'User ID',
        ];
    }

    public function getId(){
        return $this->client_id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOauthAccessToken()
    {
        return $this->hasMany(OauthAccessToken::className(), ['client_id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOauthRefreshToken()
    {
        return $this->hasMany(OauthRefreshToken::className(), ['client_id' => 'client_id']);
    }
}