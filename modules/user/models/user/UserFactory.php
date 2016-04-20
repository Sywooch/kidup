<?php

namespace user\models\user;


class UserFactory extends User
{
    public function create(){
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $this->confirmed_at = time();

        $this->trigger(self::EVENT_USER_CREATE_INIT);
        if ($this->save()) {
            $this->trigger(self::EVENT_USER_CREATE_DONE);
            return true;
        }

        return false;
    }
}
