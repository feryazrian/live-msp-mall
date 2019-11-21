<?php

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

Broadcast::channel('Marketplace.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
 
Broadcast::channel('counter.{toUserId}', function ($counter, $toUserId) {
    return $counter->id == $toUserId;
});
 
Broadcast::channel('message.{toUserId}', function ($message, $toUserId) {
    return $message->id == $toUserId;
});
 
Broadcast::channel('message.content.{sender}.{receiver}', function ($message, $sender, $receiver) {
    return $message->id == $sender;
});