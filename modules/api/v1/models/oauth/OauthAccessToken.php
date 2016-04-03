<?php

namespace api\v1\models\oauth;

use user\models\user\User;
use Yii;

/**
 * This is the model class for table "oauth_access_tokens".
 *
 * @property string $access_token
 * @property string $client_id
 * @property integer $user_id
 * @property string $expires
 * @property string $scope
 *
 * @property OauthClient $client
 * @property User $user
 */
class OauthAccessToken extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oauth_access_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['access_token', 'client_id', 'expires'], 'required'],
            [['user_id', 'expires'], 'integer'],
            [['expires'], 'safe'],
            [['access_token'], 'string', 'max' => 40],
            [['client_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'access_token' => 'Access Token',
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
     * Makes a new OauthAccessToken
     * @param User $user
     * @param OauthClient $client
     * @return OauthAccessToken
     */
    public static function make(User $user, OauthClient $client)
    {
        $token = new OauthAccessToken();
        $token->expires = time() + 3600*24;
        $token->user_id = $user->id;
        $token->client_id = $client->client_id;
        $token->access_token = self::getRandomToken() ;
        $token->save();
        return $token;
    }

    /**
     * Generates a random token, [a-z0-9]
     * @return mixed
     */
    public static function getRandomToken(){
        $var = strtolower(\Yii::$app->security->generateRandomString(40));
        $var = str_replace('-', rand(0,9),$var);
        return str_replace('_', rand(0,9),$var);
    }
}