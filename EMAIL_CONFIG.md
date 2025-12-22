# Configuration Email pour Walab

## Problème actuel

Les emails OTP ne s'envoyaient **PAS** car `MAIL_MAILER=log` dans le `.env`.  
Ce mode écrit les emails dans `storage/logs/laravel.log` au lieu de les envoyer.

## olutions disponibles

### Option 1 : Gmail SMTP (Activé par défaut maintenant) ✅

**Configuration actuelle dans `.env` :**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=houinsourock89@gmail.com
MAIL_PASSWORD=qgzaakyrcinsdpzs
MAIL_ENCRYPTION=tls
```

**LIMITES Gmail :**
- **500 emails par jour** maximum
- Risque de blocage si envoi massif
- Peut être marqué comme spam
- Nécessite un "App Password" Google (déjà configuré)

**Avantages :**
- Gratuit
- Configuration simple
- Fonctionne immédiatement

---

### Option 2 : MailerSend API (RECOMMANDÉ pour production) 🚀

**Pour activer MailerSend :**

1. Dans `.env`, commentez Gmail et décommentez MailerSend :
```env
MAIL_MAILER=mailersend
# MAIL_MAILER=smtp
```

2. Vérifiez que votre API key est valide sur [mailersend.com](https://app.mailersend.com/)

**Avantages :**
- **12 000 emails GRATUITS par mois** (plan free)
- Meilleure délivrabilité (moins de spam)
- Dashboard avec statistiques
- Pas de blocage Gmail
- Support professionnel

**Configuration actuelle :**
```env
MAILERSEND_API_KEY=mlsn.5c395dfa44919b71fb69fbc85bde2760eff81045781f2b8d9b57ea97985a9083
```

**À vérifier :**
1. Connectez-vous sur [MailerSend](https://app.mailersend.com/)
2. Vérifiez que le domaine `houinsourock89@gmail.com` est vérifié
3. Si vous utilisez un domaine personnalisé (ex: `noreply@walab.com`), il faut :
   - Ajouter le domaine sur MailerSend
   - Configurer les DNS (SPF, DKIM, DMARC)
   - Mettre à jour `MAIL_FROM_ADDRESS` dans `.env`

---

### Option 3 : Mode développement (Logs uniquement)

**Pour tester sans envoyer d'emails réels :**
```env
MAIL_MAILER=log
```

Les emails seront écrits dans `storage/logs/laravel.log` avec le code OTP visible.

---

## 🔧 Après changement de configuration

**IMPORTANT :** Redémarrez les services Laravel :

```bash
# Arrêter les services
php artisan config:clear
php artisan cache:clear

# Redémarrer le serveur
php artisan serve
```

---

## Quotas et limites

| Service | Plan Gratuit | Emails/jour | Emails/mois |
|---------|--------------|-------------|-------------|
| **Gmail SMTP** | Oui | ~500 | ~15,000 |
| **MailerSend** | Oui | 400 | 12,000 |
| **MailerSend Pro** | 19$/mois | ~3,333 | 100,000 |

---

## Debug : Vérifier si les emails s'envoient

### 1. Vérifier les logs Laravel :
```bash
tail -f storage/logs/laravel.log
```

### 2. Tester l'envoi d'un email :
```bash
php artisan tinker

# Dans tinker :
$user = User::first();
$user->notify(new \App\Notifications\VerifyEmailNotification());
exit
```

### 3. Vérifier la queue (si activée) :
```bash
php artisan queue:work
```

---

##  Recommandation

**Pour la production : Utilisez MailerSend**
- Plus fiable
- Meilleur quota gratuit
- Statistiques détaillées
- Moins de risque de spam

**Pour le développement : Utilisez log**
- Pas d'emails réels envoyés
- Codes OTP visibles dans les logs
- Pas de quota à gérer

---

## Notes importantes

1. **Les codes OTP expirent après 30 minutes**
2. **Un nouveau code invalide l'ancien** (pour sécurité)
3. **Les emails de vérification sont envoyés à l'inscription**
4. **Les praticiens/labos nécessitent aussi une validation admin** après vérification email

---

## Checklist de mise en production

- [ ] Choisir le service d'email (MailerSend recommandé)
- [ ] Configurer le `.env` correctement
- [ ] Vérifier le domaine d'envoi
- [ ] Tester l'envoi d'OTP
- [ ] Vérifier les logs d'erreur
- [ ] Redémarrer les services Laravel
- [ ] Tester l'inscription complète (client + praticien)
- [ ] Vérifier que les emails arrivent bien (pas en spam)

---

**Dernière mise à jour :** 20 décembre 2025
