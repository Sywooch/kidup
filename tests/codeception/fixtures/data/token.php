<?php
use app\modules\mail\models\Token;
$time = time();
return [
    'confirmation' => [
        'user_id'    => 2,
        'code'       => 'NO2aCmBIjFQX624xmAc3VBu7Th3NJoa6',
        'type'       => Token::TYPE_CONFIRMATION,
        'created_at' => $time,
    ],
    'expired_recovery' => [
        'user_id'    => 5,
        'code'       => 'a5839d0e73b9c525942c2f59e88c1aaf',
        'type'       => Token::TYPE_RECOVERY,
        'created_at' => $time - 21601,
    ],
    'recovery' => [
        'user_id'    => 6,
        'code'       => '6f5d0dad53ef73e6ba6f01a441c0e602',
        'type'       => Token::TYPE_RECOVERY,
        'created_at' => $time,
    ]
];