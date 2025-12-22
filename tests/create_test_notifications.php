<?php

/**
 * Script pour créer des notifications de test pour admin et labo
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Notifications\CommandeNotification;
use App\Notifications\PractitionerContactNotification;

echo "=== Création de notifications de test ===\n\n";

// 1. Notification pour Admin
echo "1. Création notification pour Admin...\n";
$admin = User::where('role_id', 1)->first();

if ($admin) {
    echo "   Admin trouvé: {$admin->firstname} {$admin->lastname} (ID: {$admin->id})\n";
    
    // Notification de commande
    $admin->notify(new CommandeNotification(
        'Nouvelle Commande',
        'Une nouvelle commande #TEST123456 a été passée par Client Test'
    ));
    
    // Notification de contact praticien
    $admin->notify(new PractitionerContactNotification(
        'Contact Praticien',
        'Nouveau contact client-praticien',
        "L'utilisateur Jean Dupont vient de contacter le Médecin généraliste Dr. Martin",
        "/practitioner/show/1"
    ));
    
    echo "   Notifications créées pour admin!\n\n";
} else {
    echo "   Aucun admin trouvé!\n\n";
}

// 2. Notification pour Laboratoire
echo "2. Création notification pour Laboratoire...\n";
$labo = User::where('role_id', 5)->first();

if ($labo) {
    echo "   Labo trouvé: {$labo->firstname} {$labo->lastname} (ID: {$labo->id})\n";
    
    // Notification de nouvelle commande assignée
    $labo->notify(new CommandeNotification(
        'Commande Assignée',
        'Une nouvelle commande #TEST789012 vous a été assignée. Code: TEST789012'
    ));
    
    // Notification de commande urgente
    $labo->notify(new CommandeNotification(
        'Commande Urgente',
        'Une commande urgente #URGENT123 nécessite votre attention immédiate'
    ));
    
    echo "   Notifications créées pour laboratoire!\n\n";
} else {
    echo "   Aucun laboratoire trouvé!\n\n";
}

// Vérification
echo "3. Vérification des notifications créées:\n\n";

if ($admin) {
    $adminNotifCount = $admin->notifications()->count();
    $adminUnreadCount = $admin->unreadNotifications()->count();
    echo "   Admin - Total: {$adminNotifCount}, Non lues: {$adminUnreadCount}\n";
}

if ($labo) {
    $laboNotifCount = $labo->notifications()->count();
    $laboUnreadCount = $labo->unreadNotifications()->count();
    echo "   Labo - Total: {$laboNotifCount}, Non lues: {$laboUnreadCount}\n";
}

echo "\n=== Test terminé ===\n";
echo "\nConnectez-vous maintenant sur les dashboards pour voir les notifications!\n";
echo "- Admin: houinsourock89+admin1@gmail.com\n";
echo "- Labo: houinsourock89+labo1@gmail.com\n";
