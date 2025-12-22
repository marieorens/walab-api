<?php

use App\Models\ChatCommande;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
Broadcast::routes();

Broadcast::channel('test', function ($user) {
    return true;
});

Broadcast::channel('chats.{code}', function ($user, $code) {
    // $access = 
    // return $user->id === ChatCommande::where('code', $code)->first()->user_id;
    return true;
});

Broadcast::channel('chats', function ($user) {
    return true;
});

Broadcast::channel('test.{code}', function ($user, $code) {
    return true;
});
