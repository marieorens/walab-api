<?php



require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Notifications\PractitionerContactNotification;
use Illuminate\Support\Facades\Notification;

echo "=== Test Notification System ===\n\n";

// Get an admin user
$admin = User::where('role_id', 1)->first();

if (!$admin) {
    echo "Erreur: Aucun admin trouvé\n";
    exit(1);
}

echo "Admin trouvé: {$admin->firstname} {$admin->lastname} (ID: {$admin->id})\n";

// Count existing notifications
$existingCount = $admin->notifications()->count();
echo "Notifications existantes: {$existingCount}\n\n";

// Send a test notification
echo "Envoi d'une notification de test...\n";
$admin->notify(new PractitionerContactNotification(
    'Contact Praticien',
    'Test de notification',
    "L'utilisateur Test Client vient de contacter le Médecin généraliste Dr. Test",
    "/practitioner/show/1"
));

echo "Notification envoyée!\n\n";

// Verify notification was created
$newCount = $admin->notifications()->count();
echo "Nouvelles notifications: {$newCount}\n";

if ($newCount > $existingCount) {
    echo "Succès! La notification a été créée.\n";
    
    // Show the last notification
    $lastNotif = $admin->notifications()->latest()->first();
    echo "\nDernière notification:\n";
    echo "  ID: {$lastNotif->id}\n";
    echo "  Type: {$lastNotif->type}\n";
    echo "  Data: " . json_encode($lastNotif->data, JSON_PRETTY_PRINT) . "\n";
    echo "  Read at: " . ($lastNotif->read_at ? $lastNotif->read_at : 'Non lu') . "\n";
} else {
    echo "Erreur: La notification n'a pas été créée\n";
}

echo "\n=== Test terminé ===\n";
