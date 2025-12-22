<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Commande;
use App\Models\User;

echo "=== Test de la fonctionnalite QR Code ===\n\n";

$commande = Commande::whereNotNull('agent_id')->first();

if (!$commande) {
    echo "Aucune commande avec agent trouvee\n";
    exit(1);
}

echo "Commande trouvee: ID {$commande->id}, Code: {$commande->code}\n";
echo "  Agent ID: {$commande->agent_id}\n";

if (empty($commande->qr_code_base64)) {
    echo "  QR Code absent - Generation en cours...\n";
    $commande->generateAndStoreQrCode();
    $commande->refresh();
}

if (!empty($commande->qr_code_base64)) {
    echo "  QR Code present (longueur: " . strlen($commande->qr_code_base64) . " caracteres)\n";
} else {
    echo "  QR Code manquant apres generation\n";
    exit(1);
}

if ($commande->verification_token) {
    echo "  Token de verification: " . substr($commande->verification_token, 0, 20) . "...\n";
} else {
    echo "  Token de verification manquant\n";
    exit(1);
}

if ($commande->token_expires_at) {
    echo "  Expire le: {$commande->token_expires_at}\n";
} else {
    echo "  Date d'expiration manquante\n";
    exit(1);
}

$url = $commande->getQrCodeUrl();
echo "  URL de verification: {$url}\n";

$agent = User::find($commande->agent_id);
if ($agent) {
    echo "  Agent: {$agent->firstname} {$agent->lastname} (ID: {$agent->id})\n";
} else {
    echo "  Agent non trouve\n";
}

echo "  " . ($commande->is_verified ? "Verifie" : "En attente de verification") . "\n";

echo "\nResume:\n";
echo "Toutes les fonctionnalites QR Code sont operationnelles\n";
echo "Le QR code devrait s'afficher pour l'agent ID {$commande->agent_id}\n";
echo "L'URL frontend configuree: " . config('app.frontend_url') . "\n";
