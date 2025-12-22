<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\NewCommandeMail;
use App\Models\Commande;

try {
    echo "🧪 Test d'envoi d'email\n";
    echo "=======================\n\n";
    
    // Récupérer une commande
    $commande = Commande::with(['client', 'examen.laboratorie', 'type_bilan.laboratorie'])->first();
    
    if (!$commande) {
        echo "❌ Aucune commande trouvée\n";
        exit(1);
    }
    
    echo "✅ Commande trouvée: " . $commande->code . "\n";
    
    // Trouver le laboratoire
    $laboratoire = null;
    if ($commande->examen && $commande->examen->laboratorie) {
        $laboratoire = $commande->examen->laboratorie;
    } elseif ($commande->type_bilan && $commande->type_bilan->laboratorie) {
        $laboratoire = $commande->type_bilan->laboratorie;
    }
    
    if (!$laboratoire) {
        echo "❌ Aucun laboratoire associé\n";
        exit(1);
    }
    
    if (!$laboratoire->user) {
        echo "❌ Le laboratoire n'a pas d'utilisateur\n";
        exit(1);
    }
    
    $email = $laboratoire->user->email;
    echo "Laboratoire: " . $laboratoire->name . "\n";
    echo "Email destination: " . $email . "\n\n";
    
    echo "Envoi de l'email en cours...\n";
    
    Mail::to($email)->send(new NewCommandeMail($commande, $laboratoire));
    
    echo "Email envoyé avec succès !\n";
    echo "\nVérifiez la boîte mail: " . $email . "\n";
    
} catch (Exception $e) {
    echo "\nERREUR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
