<?php
namespace user\models;

/**
 * @property integer $id          Id
 * @property integer $user_id     User id, null if account is not bind to user
 * @property string $provider    Name of service
 * @property string $client_id   Account id
 * @property string $data        Account properties returned by social network (json encoded)
 * @property string $decodedData Json-decoded properties
 * @property User $user        User that this account is connected for.
 *
 * @property \user\Module $module
 *
 */
class Account extends SocialAccount
{
    /** @var \user\Module */
    protected $module;

    /** @var */
    private $_data;

    /** @inheritdoc */
    public function init()
    {
        $this->module = \Yii::$app->getModule('user');
    }

    /** @inheritdoc */
    public static function tableName()
    {
        return '{{%social_account}}';
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }

    /**
     * @return bool Whether this social account is connected to user.
     */
    public function getIsConnected()
    {
        return $this->user_id != null;
    }

    /**
     * @return mixed Json decoded properties.
     */
    public function getDecodedData()
    {
        if ($this->_data == null) {
            $this->_data = json_decode($this->data);
        }

        return $this->_data;
    }
}