<?php

/**
 * Script de vérification de la configuration des notifications
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== Vérification de la configuration des notifications ===\n\n";

// 1. Vérifier les rôles admin
echo "1. Rôles Admin:\n";
$admins = User::whereIn('role_id', [1, 4])->get();
echo "   Nombre d'admins trouvés: " . $admins->count() . "\n";
foreach ($admins as $admin) {
    $notifCount = $admin->notifications()->count();
    $unreadCount = $admin->unreadNotifications()->count();
    echo "   - {$admin->firstname} {$admin->lastname} (Role ID: {$admin->role_id})\n";
    echo "     Email: {$admin->email}\n";
    echo "     Notifications: {$notifCount} (Non lues: {$unreadCount})\n";
}

echo "\n2. Rôles Laboratoire:\n";
$labos = User::where('role_id', 5)->get();
echo "   Nombre de laboratoires trouvés: " . $labos->count() . "\n";
foreach ($labos as $labo) {
    $notifCount = $labo->notifications()->count();
    $unreadCount = $labo->unreadNotifications()->count();
    echo "   - {$labo->firstname} {$labo->lastname} (Role ID: {$labo->role_id})\n";
    echo "     Email: {$labo->email}\n";
    echo "     Notifications: {$notifCount} (Non lues: {$unreadCount})\n";
}

echo "\n3. Vérification des fichiers de vue:\n";
$files = [
    'resources/views/admin/notifications.blade.php' => 'Vue Admin',
    'resources/views/laboratoire/notifications.blade.php' => 'Vue Laboratoire',
];

foreach ($files as $file => $desc) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        echo "   ✓ {$desc}: OK\n";
    } else {
        echo "   ✗ {$desc}: MANQUANT\n";
    }
}

echo "\n4. Vérification des routes:\n";
try {
    $routes = \Route::getRoutes();
    $notifRoutes = ['notifications.index', 'notifications.markAsRead', 'notifications.markAllAsRead', 'notifications.unreadCount'];
    
    foreach ($notifRoutes as $routeName) {
        if ($routes->hasNamedRoute($routeName)) {
            echo "   ✓ Route '{$routeName}': OK\n";
        } else {
            echo "   ✗ Route '{$routeName}': MANQUANT\n";
        }
    }
} catch (\Exception $e) {
    echo "   Erreur lors de la vérification des routes: " . $e->getMessage() . "\n";
}

echo "\n=== Configuration vérifiée ===\n\n";

echo "Instructions de test:\n";
echo "1. Connectez-vous en tant qu'admin (role_id 1 ou 4)\n";
echo "2. Cliquez sur l'icône de cloche en haut à droite\n";
echo "3. Vous devriez voir la page '/resources/views/admin/notifications.blade.php'\n";
echo "4. Répétez pour un utilisateur labo (role_id 5)\n";
echo "5. Vous devriez voir la page '/resources/views/laboratoire/notifications.blade.php'\n";
