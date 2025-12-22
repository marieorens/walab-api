<?php

/**
 * Script de test complet des notifications admin
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Notifications\CommandeNotification;
use App\Notifications\PractitionerContactNotification;

echo "=== Test Complet Notifications Admin ===\n\n";

// 1. Vérifier les rôles admin
echo "1. Vérification des rôles admin:\n";
$adminSimple = User::where('role_id', 1)->first();
$adminSup = User::where('role_id', 4)->first();

if ($adminSimple) {
    echo "   Admin Simple trouvé: {$adminSimple->firstname} {$adminSimple->lastname}\n";
    echo "   Email: {$adminSimple->email}\n";
}

if ($adminSup) {
    echo "   Admin Sup trouvé: {$adminSup->firstname} {$adminSup->lastname}\n";
    echo "   Email: {$adminSup->email}\n";
}

// 2. Créer des notifications de test
echo "\n2. Création des notifications de test:\n";

$testNotifications = [
    [
        'type' => 'Nouvelle Commande',
        'message' => 'Commande #TEST001 passée par Jean Dupont'
    ],
    [
        'type' => 'Nouveau Résultat',
        'message' => 'Laboratoire Alpha a uploadé un résultat pour la commande #TEST001'
    ],
    [
        'type' => 'Contact Praticien',
        'title' => 'Nouveau contact client-praticien',
        'message' => "L'utilisateur Marie Martin vient de contacter le Médecin généraliste Dr. Dubois"
    ]
];

$admins = User::whereIn('role_id', [1, 4])->get();

foreach ($testNotifications as $notif) {
    foreach ($admins as $admin) {
        if (isset($notif['title'])) {
            // Notification de contact praticien
            $admin->notify(new PractitionerContactNotification(
                $notif['type'],
                $notif['title'],
                $notif['message'],
                null
            ));
        } else {
            // Notification de commande/résultat
            $admin->notify(new CommandeNotification(
                $notif['type'],
                $notif['message']
            ));
        }
    }
    echo "   Notification '{$notif['type']}' créée pour " . $admins->count() . " admin(s)\n";
}

// 3. Vérifier les notifications
echo "\n3. Vérification des notifications créées:\n";

foreach ($admins as $admin) {
    $totalNotif = $admin->notifications()->count();
    $unreadNotif = $admin->unreadNotifications()->count();
    
    echo "\n   {$admin->firstname} {$admin->lastname} (Role ID: {$admin->role_id}):\n";
    echo "   - Total: {$totalNotif}\n";
    echo "   - Non lues: {$unreadNotif}\n";
    
    // Afficher les 3 dernières
    $lastNotifs = $admin->notifications()->latest()->take(3)->get();
    echo "   - Dernières notifications:\n";
    foreach ($lastNotifs as $n) {
        $type = $n->data['type'] ?? 'N/A';
        $title = $n->data['title'] ?? $n->data['data'] ?? 'N/A';
        echo "     * {$type}: " . substr($title, 0, 50) . "...\n";
    }
}

echo "\n=== Test Terminé ===\n\n";

echo "Résumé des fonctionnalités:\n";
echo "1. Nouvelles commandes → Admins reçoivent notification\n";
echo "2. Résultats uploadés → Admins reçoivent notification\n";
echo "3. Contact praticien → Admins reçoivent notification\n";
echo "\nPage de notifications séparées:\n";
echo "- Admin: /resources/views/admin/notifications.blade.php\n";
echo "- Labo: /resources/views/laboratoire/notification/notifications.blade.php\n";
echo "\nBouton 'Voir détails' retiré de la page admin.\n";
