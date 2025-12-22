#!/bin/bash

# Script de test pour l'envoi d'emails
# Usage: ./test-email-notifications.sh

cd "$(dirname "$0")"

echo "Test du système d'envoi d'emails"
echo "===================================="
echo ""

# Test 1: Vérifier la configuration SMTP
echo "1. Vérification de la configuration SMTP..."
grep "MAIL_MAILER" .env
grep "MAIL_HOST" .env
grep "MAIL_USERNAME" .env
echo ""

# Test 2: Test d'envoi simple
echo "2. Test d'envoi d'email simple..."
php artisan tinker --execute="
use Illuminate\Support\Facades\Mail;
use App\Mail\NewCommandeMail;
use App\Models\Commande;
use App\Models\Laboratorie;

try {
    \$commande = Commande::with(['client', 'examen.laboratorie', 'type_bilan.laboratorie'])->first();
    if (\$commande) {
        \$labo = \$commande->examen ? \$commande->examen->laboratorie : (\$commande->type_bilan ? \$commande->type_bilan->laboratorie : null);
        if (\$labo && \$labo->user) {
            echo 'Envoi d\'un email de test à: ' . \$labo->user->email . PHP_EOL;
            Mail::to(\$labo->user->email)->send(new NewCommandeMail(\$commande, \$labo));
            echo 'Email envoyé avec succès!' . PHP_EOL;
        } else {
            echo 'Aucun laboratoire trouvé pour cette commande' . PHP_EOL;
        }
    } else {
        echo 'Aucune commande trouvée dans la base' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Erreur: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "3. Vérifier les logs..."
tail -20 storage/logs/laravel.log | grep -i "email\|mail"

echo ""
echo "Test terminé! Vérifiez la boîte mail."