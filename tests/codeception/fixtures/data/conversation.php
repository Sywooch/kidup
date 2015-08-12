<?php
$time = time();
$conversation = array(
    array( // row #0
        'id' => 1,
        'initiater_user_id' => 1,
        'target_user_id' => 2,
        'title' => 'Welcome to kidup!',
        'created_at' => $time,
        'updated_at' => $time,
        'booking_id' => 0,
    ),
    array( // row #1
        'id' => 2,
        'initiater_user_id' => 1,
        'target_user_id' => 3,
        'title' => 'Welcome to kidup!',
        'created_at' => $time,
        'updated_at' => $time,
        'booking_id' => 0,
    ),
    array( // row #2
        'id' => 3,
        'initiater_user_id' => 3,
        'target_user_id' => 2,
        'title' => 'Test',
        'created_at' => $time,
        'updated_at' => $time,
        'booking_id' => 1,
    ),
    array( // row #3
        'id' => 4,
        'initiater_user_id' => 3,
        'target_user_id' => 2,
        'title' => 'Test',
        'created_at' => $time,
        'updated_at' => $time,
        'booking_id' => 2,
    ),
    array( // row #3
        'id' => 5,
        'initiater_user_id' => 3,
        'target_user_id' => 2,
        'title' => 'Test',
        'created_at' => $time,
        'updated_at' => $time,
        'booking_id' => 3,
    ),
    array( // row #3
        'id' => 6,
        'initiater_user_id' => 3,
        'target_user_id' => 2,
        'title' => 'Test',
        'created_at' => $time,
        'updated_at' => $time,
        'booking_id' => 4,
    ),
);


return $conversation;