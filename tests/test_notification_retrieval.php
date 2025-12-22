<?php

/**
 * Test script to fetch notifications via the controller logic
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== Test Notification Retrieval ===\n\n";

// Get the admin user
$admin = User::where('role_id', 1)->first();

if (!$admin) {
    echo "Erreur: Aucun admin trouvé\n";
    exit(1);
}

echo "Testing for user: {$admin->firstname} {$admin->lastname} (ID: {$admin->id})\n\n";

// Get notifications the same way the controller does
$notifications = $admin->notifications()
    ->orderBy('created_at', 'desc')
    ->get()
    ->map(function ($notification) {
        return [
            'id' => $notification->id,
            'type' => $notification->data['type'] ?? 'Notification',
            'title' => $notification->data['title'] ?? $notification->data['type'] ?? 'Notification',
            'data' => $notification->data['data'] ?? $notification->data['body'] ?? 'Nouvelle notification',
            'body' => $notification->data['body'] ?? $notification->data['data'] ?? '',
            'url' => $notification->data['url'] ?? null,
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
            'created_at_human' => $notification->created_at->diffForHumans(),
        ];
    });

$unreadCount = $admin->unreadNotifications()->count();

echo "Total notifications: " . $notifications->count() . "\n";
echo "Unread count: {$unreadCount}\n\n";

if ($notifications->count() > 0) {
    echo "First notification:\n";
    $first = $notifications->first();
    foreach ($first as $key => $value) {
        echo "  {$key}: " . (is_string($value) || is_numeric($value) ? $value : json_encode($value)) . "\n";
    }
} else {
    echo "No notifications found!\n";
}

echo "\n=== Test terminé ===\n";
