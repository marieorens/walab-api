#!/bin/bash

echo " Test d'envoi d'email OTP - Walab"
echo "===================================="
echo ""

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Vérifier la configuration email
echo " Configuration email actuelle:"
echo ""
php artisan tinker --execute="echo 'MAIL_MAILER: ' . config('mail.default') . PHP_EOL;"
php artisan tinker --execute="echo 'MAIL_FROM: ' . config('mail.from.address') . PHP_EOL;"
echo ""

# Demander l'email de test
read -p " Entrez un email pour tester (ou appuyez sur Entrée pour utiliser le premier user) : " TEST_EMAIL

if [ -z "$TEST_EMAIL" ]; then
    echo "${YELLOW}Utilisation du premier utilisateur de la base de données...${NC}"
    echo ""
fi

# Test d'envoi
echo " Envoi du code OTP..."
echo ""

if [ -z "$TEST_EMAIL" ]; then
    php artisan tinker <<EOF
\$user = \App\Models\User::first();
if (\$user) {
    echo " Utilisateur: " . \$user->firstname . " " . \$user->lastname . " (" . \$user->email . ")" . PHP_EOL;
    try {
        \$user->notify(new \App\Notifications\VerifyEmailNotification());
        echo "${GREEN} Email OTP envoyé avec succès !${NC}" . PHP_EOL;
        echo " Vérifiez les logs pour voir le code OTP si MAIL_MAILER=log" . PHP_EOL;
        echo " Fichier de log: storage/logs/laravel.log" . PHP_EOL;
    } catch (\Exception \$e) {
        echo "${RED} Erreur: " . \$e->getMessage() . "${NC}" . PHP_EOL;
    }
} else {
    echo "${RED} Aucun utilisateur trouvé dans la base de données${NC}" . PHP_EOL;
}
exit
EOF
else
    php artisan tinker <<EOF
\$user = \App\Models\User::where('email', '$TEST_EMAIL')->first();
if (\$user) {
    echo " Utilisateur: " . \$user->firstname . " " . \$user->lastname . " (" . \$user->email . ")" . PHP_EOL;
    try {
        \$user->notify(new \App\Notifications\VerifyEmailNotification());
        echo "${GREEN} Email OTP envoyé avec succès !${NC}" . PHP_EOL;
        echo " Vérifiez les logs pour voir le code OTP si MAIL_MAILER=log" . PHP_EOL;
        echo " Fichier de log: storage/logs/laravel.log" . PHP_EOL;
    } catch (\Exception \$e) {
        echo "${RED} Erreur: " . \$e->getMessage() . "${NC}" . PHP_EOL;
    }
} else {
    echo "${RED} Utilisateur non trouvé avec l'email: $TEST_EMAIL${NC}" . PHP_EOL;
}
exit
EOF
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Conseils de debug :"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "1. Voir les derniers logs:"
echo "   tail -f storage/logs/laravel.log"
echo ""
echo "2. Si MAIL_MAILER=log, le code OTP est dans les logs"
echo ""
echo "3. Si MAIL_MAILER=smtp ou mailersend, vérifiez:"
echo "   - La boîte de réception"
echo "   - Le dossier spam"
echo "   - Les credentials dans .env"
echo ""
echo "4. Changer la configuration email:"
echo "   nano .env"
echo "   (puis: php artisan config:clear)"
echo ""
