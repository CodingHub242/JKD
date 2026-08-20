<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('admin.dashboard', function ($user) {
    return $user && $user->is_admin;
});

Broadcast::channel('admin.submissions', function ($user) {
    return $user && $user->is_admin;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    return $user && $user->is_admin;
});
