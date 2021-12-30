<?php

use Illuminate\Support\Facades\Broadcast;
use Musonza\Chat\Facades\ChatFacade;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('message.received.{id}', function($user, $id) {
  return (int) $user->id === (int) $id;
});

// Broadcast::channel('mc-chat-conversation.{conversationId}', function($user, $conversationId) {
//     $conversation = ChatFacade::conversations()->getById($conversationId);
//     if(!$conversation) {
//       return false;
//     } else {
//       $participants = $conversation->getParticipants()->keyBy('id')->toArray();
//       if(array_key_exists($user->id, $participants)) {
//         return true;
//       } else {
//         return false;
//       }
//     }
// });


