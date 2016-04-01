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

use user\models\user\User;

/**
 * Class TokenFactory can create a token for a given user.
 *
 * @package mail\models
 */
class TokenFactory
{
    /**
     * @param   User $user User to create the token for.
     * @param   int $type Type of the token (see Token class for possible types).
     * @return  Token The token.
     */
    public static function create(User $user, $type)
    {
        $token = new Token();
        $token->setAttributes([
            'user_id' => $user->id,
            'type' => $type,
        ]);
        $token->save();
        return $token;
    }

    public static function createRecovery(User $user)
    {
        return self::create($user, Token::TYPE_RECOVERY);
    }
}