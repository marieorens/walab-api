<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;

class PushSubscriptionController extends Controller
{
    protected $auth;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    public function update(Request $request)
    {
        $request->validate([
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required',
        ]);

        $user = $this->auth->user();

        $user->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'],
            $request->keys['auth'],
            'aes128gcm'
        );

        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $request->validate(['endpoint' => 'required']);
        $this->auth->user()->deletePushSubscription($request->endpoint);
        return response()->json(['success' => true]);
    }
}
