<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com//>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace user\models\token;

use yii\helpers\Url;

/**
 * Class Token.
 */
class Token extends TokenBase
{
    const TYPE_CONFIRMATION = 0;
    const TYPE_RECOVERY = 1;
    const TYPE_CONFIRM_NEW_EMAIL = 2;
    const TYPE_CONFIRM_OLD_EMAIL = 3;
    const TYPE_PHONE_CODE = 4;

    /**
     * @return string
     */
    public function getUrl()
    {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
                $route = '/user/registration/confirm';
                break;
            case self::TYPE_RECOVERY:
                $route = '/user/recovery/reset';
                break;
            case self::TYPE_CONFIRM_NEW_EMAIL:
            case self::TYPE_CONFIRM_OLD_EMAIL:
                $route = '/user/settings/confirm';
                break;
            default:
                throw new \RuntimeException;
        }

        return Url::to([$route, 'id' => $this->user_id, 'code' => $this->code], true);
    }

    /**
     * @return bool Whether token has expired.
     */
    public function getIsExpired()
    {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
            case self::TYPE_CONFIRM_NEW_EMAIL:
            case self::TYPE_CONFIRM_OLD_EMAIL:
                $expirationTime = 365 * 24 * 60 * 60;
                break;
            case self::TYPE_RECOVERY:
                $expirationTime = 365 * 24 * 60 * 60;
                break;
            case self::TYPE_PHONE_CODE:
                $expirationTime = 1 * 24 * 60 * 60;
                break;
            default:
                throw new \RuntimeException;
        }

        return ($this->created_at + $expirationTime) < time();
    }

    /** @inheritdoc */
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if ($this->type == self::TYPE_PHONE_CODE) {
                $this->setAttribute('code', '' . rand(10000, 99999));
            } else {
                $this->setAttribute('code', \Yii::$app->security->generateRandomString());
            }
        }

        return parent::beforeValidate();
    }
}