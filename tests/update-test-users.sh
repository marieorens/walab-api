#!/bin/bash

echo "🔧 Mise à jour des utilisateurs de test pour la vérification d'email"
echo "====================================================================="
echo ""
echo "Cette commande va UNIQUEMENT mettre à jour les utilisateurs de test"
echo "Elle ne touche PAS aux données réelles des clients/praticiens"
echo ""

# Exécuter la commande SQL via artisan tinker
php artisan tinker <<EOF
\$testEmails = [
    'test@adminsup.com',
    'test@admin.com',
    'test@agent.com',
    'test@client.com',
    'labo@test.com',
    'test@laboratoire.com'
];

\$updated = \App\Models\User::whereIn('email', \$testEmails)
    ->whereNull('email_verified_at')
    ->update(['email_verified_at' => now()]);

echo "{$updated} utilisateur(s) de test mis à jour\n";

// Afficher le statut
\$users = \App\Models\User::whereIn('email', \$testEmails)->get();
foreach (\$users as \$user) {
    \$status = \$user->email_verified_at ? 'Vérifié' : ' Non vérifié';
    echo "{$status} - {\$user->email}\n";
}

exit
EOF

echo ""
echo "Terminé ! Les utilisateurs de test peuvent maintenant se connecter sans vérification."
echo ""
