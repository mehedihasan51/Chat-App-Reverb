<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('chat.{receiver_id}', function ($user, $receiver_id) {
//     return (int) $user->id === (int) $receiver_id;
// });


Broadcast::channel('chat.{receiver_id}', function ($user, $receiver_id) {
    // Allow access if the user is authenticated and the receiver_id matches the user's ID
    return (int) $user->id === (int) $receiver_id;
});